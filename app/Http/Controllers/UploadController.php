<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Image;
use Storage;
use Aws\Rekognition\RekognitionClient;
use Aws\Lambda\LambdaClient;
use Aws\Credentials\Credentials;


class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render(
            'Upload/Upload',
            [
                // lazy load tags on reload
                'tags' => Inertia::lazy(fn () => Tag::select('id', 'name', 'slug')->get())
            ]
        );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render("Upload");
    }

    /**
     * Store a newly created resource in storage.
     * This function will be used to store the initial data
     * when a user selects a file. This entry will be updated
     * When the actual file is sent.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $files = $request->validate([
            '*.tempId' => 'required|integer',
            '*.title' => 'required|string',
            '*.size' => 'required|integer',

        ]);
        // will save the file id as an array
        // token will be used as key. From frontend
        // we will use this token to match the correct
        // photo id
        $resp_data = [];

        foreach ($files as $file) {
            $id =  Photo::create([
                'title' => $file['title'],
                'user_id' => Auth::user()->id,
                'size' => $file['size']
            ])->id;
            array_push($resp_data, ['tempId' => $file['tempId'], 'id' => $id]);
        }
        return $resp_data;
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'title' => 'string',
            'description' => 'string',
            'tags' => 'exists:tags,id'
        ]);
        $photo = Photo::where('token', '=', $data['token'])->get();
        if (!$photo) {
            // if photo doesn't exist then we should create the photo
            $photo = Photo::create([
                'token' => $data['token'],
                'title' => $data['title'] ? $data['title'] : ''
            ]);
        }
        // if tags are supplied then we sync it with pivot table
        if (array_key_exists('tags', $data)) {
            $photo->tags->sync($data['tags']);
            // removing tags from $data array for updating $photo
            unset($data['tags']);
        }
        // we dont want to update token. So removing it from array
        unset($data['token']);

        $photo->update($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFile(Request $request)
    {

        $data = $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,zip,psd',
            'id' => 'required|exists:photos,id',

        ]);

        // generating a random filename for photo
        $fileName = bin2hex(random_bytes(32)) . '.' . $data['file']->getClientOriginalExtension(); //
        $imgsize = getimagesize($data['file']->getPathName());
        //Storage::disk('s3_fullsize')->putFile("full_size/", $fileName, $data['file'],);

        $data['file']->storeAs("full_size/", $fileName, 's3_fullsize');

        // updating the photo entry

        $photo = Photo::where("id", $data['id'])->first();
        $photo->update([
            'title' => $data['file']->getClientOriginalName(),
            'file_name' => $fileName,
            'size' => $data['file']->getSize(),
            'height' => $imgsize[1],
            'width' => $imgsize[0],
            'file_type' => $data['file']->getClientMimeType(),
            'user_id' => Auth::user()->id,
            'should_process' => False,

        ]);

        // $full_image = Image::make($data['file']->path());
        // // resizes the image to have 200px width and preserves aspectRatio
        // $resizedImage = $full_image->resize(null, 200, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->stream();
        // Storage::disk('local')->put('thumbnail/' . $fileName, $resizedImage->__toString());

        // $resizedImage = $full_image->resize(null, 1000, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->encode(
        //     'jpeg',
        //     75
        // )->stream();
        // Storage::disk('local')->put('preview/' . $fileName, $resizedImage->__toString());

        // to-do
        // move label detection code to queue
        $credentials = new Credentials(config('services.ses.key'), config('services.ses.secret'));
        $client = new RekognitionClient(array(
            'credentials' => $credentials,
            'region' => config('services.ses.region'),
            'version' => 'latest'
        ));
        $result = $client->detectLabels(
            [
                'Image' => [
                    'S3Object' => [
                        'Bucket' => config('aws.fullsize_bucket'),
                        'Name' => 'full_size/' . $fileName,
                    ],
                ]
            ]
        );
        $label_ids = [];
        $label_scores = [];

        foreach ($result['Labels'] as $label) {

            $label_id = Label::firstOrCreate([
                "name" => $label['Name']
            ])->id;
            array_push($label_ids, $label_id);
            array_push($label_scores, ['score' => round($label['Confidence'])]);
        }
        //dd(array_combine($label_ids, $label_scores));
        $photo->labels()->sync(array_combine($label_ids, $label_scores));


        $client = new LambdaClient(array(
            'credentials' => $credentials,
            'region' => config('services.ses.region'),
            'version' => 'latest'
        ));
        $result = $client->invoke(array(
            // FunctionName is required
            'FunctionName' => 'arn:aws:lambda:us-east-1:728758055541:function:photo_post_upload',
            'InvocationType' => 'RequestResponse',
            'LogType' => 'None',
            //'ClientContext' => 'string',
            'Payload' => json_encode(array(
                'file_name' => $fileName
            )),
            //'Qualifier' => 'string',
        ));

        return http_response_code(204);
    }
    /**
     * Cancel an upload while its still uploading
     * This function will be used from cancel button
     * in /upload page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancelUpload(Request $request)
    {
        $data = $request->validate(['*.id' => "required|integer"]);
        foreach ($data as $single_photo) {

            $photo = Photo::where([
                ['id', "=", $single_photo['id']],
                ['user_id', "=", Auth::id()],
                ['created_at', ">=", Carbon::now()->subHours(12)->toDateTimeString()],
            ])->first();

            if ($photo) {
                $photo->forceDelete();
            }
        }

        return response('', 200);
    }
    /**
     * Adds tag to photo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addTag(Request $request)
    {
        $data = $request->validate([
            'fileId' => "required|exists:photos,id",
            'tagId' => "nullable|exists:tags,id"
        ]);
        //dd($data);
        $photo = Photo::find($data['fileId']);
        $photo->tags()->attach($data['tagId']);


        return response('', 200);
    }
    /**
     * Removes tag to photo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeTag(Request $request)
    {
        $data = $request->validate([
            'fileId' => "required|exists:photos,id",
            'tagId' => "nullable|exists:tags,id"
        ]);
        //dd($data);
        $photo = Photo::find($data['fileId']);
        $photo->tags()->detach($data['tagId']);


        return response('', 200);
    }

    public function updateDetails(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:photos,id',
            'title' => 'string',
            'description' => 'string',
            'copyright' => 'string',
        ]);

        // photo details can only 9be updated in upload time
        // if the photo belongs to the authenticated user
        $photo = Photo::where([
            ['id', '=', $data['id']],
            ['user_id', "=", Auth::id()],
        ])->first();
        if ($photo) {
            // removed the id from $data array. as we dont want to update the id
            unset($data['id']);

            // updating the photo with updated details
            $photo->update($data);
        }
        return response('', 200);
    }
}
