<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
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

        $data = $request->validate([
            'files.*.id' => 'exists:photos,id',
            'files.*.title' => 'string',
            'files.*.description' => 'string',
            'files.*.tags' => 'exists:tags,id',
        ]);

        foreach ($data['files'] as $file) {
            $photo = Photo::create([
                'title' => $data['file']->getClientOriginalName(),
                'user_id' => Auth::user()->id,
                'should_process' => False,
                'token' => $data['token'],
            ]);

            // updating tags
            //$photo->tags->sync($file['tags']);
        }
        return http_response_code(202);
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
    public function store_file(Request $request)
    {

        $data = $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,zip,psd',
            'token' => 'required|string',
            'name' => 'string'
        ]);
        // generating a random string and getting the first 8 characters of the random
        // hex string. then adding an underscore to the file name. also all spaces are
        // removed from filename
        $fileName = bin2hex(random_bytes(32)) . '.' . $data['file']->getClientOriginalExtension(); //
        $imgsize = getimagesize($data['file']->getPathName());
        $data['file']->storeAs("full_size/", $fileName, 'local');
        // updating the photo entry
        Photo::where('token', '=', $data['token'])->update([
            'title' => $data['file']->getClientOriginalName(),
            'file_name' => $fileName,
            'size' => $data['file']->getSize(),
            'height' => $imgsize[1],
            'width' => $imgsize[0],
            'file_type' => $data['file']->getClientMimeType(),
            'user_id' => Auth::user()->id,
            'should_process' => False,
            'token' => $data['token'],
        ]);


        // $credentials = new Credentials(config('services.ses.key'), config('services.ses.secret'));
        // $client = new LambdaClient(array(
        //     'credentials' => $credentials,
        //     'region' => config('services.ses.region'),
        //     'version' => 'latest'
        // ));
        // $result = $client->invoke(array(
        //     // FunctionName is required
        //     'FunctionName' => 'arn:aws:lambda:us-east-1:728758055541:function:photo_post_upload',
        //     'InvocationType' => 'RequestResponse',
        //     'LogType' => 'None',
        //     //'ClientContext' => 'string',
        //     'Payload' => json_encode(array(
        //         'photo' => '012c84847602494063e62b1a98e023c05064f333389d5465070d901a4c96408f.jpeg'
        //     )),
        //     //'Qualifier' => 'string',
        // ));
        // dd($result['Payload']->getContents());
        return http_response_code(204);
    }
}
