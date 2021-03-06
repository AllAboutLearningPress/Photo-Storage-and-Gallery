<?php

namespace App\Jobs;

use App\Models\Label;
use App\Models\Photo;
use Aws\Lambda\LambdaClient;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification;
use DB;
use Validator;

class ProcessPhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The photo instance.
     *
     * @var \App\Models\Photo
     */
    protected $photoId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($photoId)
    {
        $this->photoId = $photoId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $photo = Photo::where('id', "=", $this->photoId)->first();

        $client = new RekognitionClient(array(
            //'credentials' => $credentials,
            'region' => config('services.ses.region'),
            'version' => 'latest'
        ));
        $result = $client->detectLabels(
            [
                'Image' => [
                    'S3Object' => [
                        'Bucket' => config('aws.fullsize_bucket'),
                        'Name' => 'full_size/' . $photo->file_name
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

        // adding the labels to the photo
        $photo->labels()->sync(array_combine($label_ids, $label_scores));

        // invoking lambda function to generate image previews and thumbnails
        $client = new LambdaClient(array(
            // 'credentials' => $credentials,
            'region' => config('services.ses.region'),
            'version' => 'latest'
        ));
        $result = $client->invoke(array(
            // FunctionName is required
            'FunctionName' => config('aws.post_upload_arn'),
            'InvocationType' => 'RequestResponse',
            'LogType' => 'None',
            //'ClientContext' => 'string',
            'Payload' => json_encode(array(
                'file_name' => $photo->file_name
            )),
            //'Qualifier' => 'string',
        ));
        $payload = $result->get('Payload');

        // validating details provided by lambda client
        $photoDetails = Validator::make(json_decode($payload, true), [
            'height' => 'required|integer',
            'width' => 'required|integer',
            'file_type' => 'required|in:jpg,jpeg,png,psd',
            'size' => 'required|integer',
            'dhash' => 'required|string',
            'sha256' => 'required|string',
        ])->validate();




        // checking for exact duplicate
        $duplicate = Photo::where('sha256', $photoDetails['sha256'])->orderBy('id', 'ASC')->first();
        //orWhere(function ($query) use ($photoDetails) {
        //   $query->where('dhash', $photoDetails['dhash']);
        //}
        if ($duplicate) {
            Notification::create([
                'data' => json_encode(['title' => 'Found Duplicate', 'body' => "\"{$photo->title}\" is a duplicate of \"{$duplicate->title}\" "]),
                'user_id' => $photo->user_id,
                'file_name' => $photo->file_name,
                'route' => json_encode(['name' => 'compare.index', 'options' => ['left' => $photo->id, 'right' => $duplicate->id]])
            ]);
        }

        // checking for color corrected or slightly zoomed photo
        $parentPhoto = DB::table('photos')
            ->selectRaw("id, dhash, BIT_COUNT(UNHEX('cfc080fcf9c0d0f8') ^ unhex(dhash)) as hd")
            ->havingRaw('hd > 0 and hd < 12 ')
            ->orderBy('hd', 'asc')
            ->first();
        if ($parentPhoto) {
            $parentPhoto->derivatives()->attach($photo->id);
        }

        // SELECT id, dhash, BIT_COUNT(UNHEX('cfc080fcf9c0d0f8') ^ unhex(dhash)) AS hd from photos HAVING hd > 0
        // select id, dhash, BIT_COUNT(UNHEX('cfc080fcf9c0d0f8') ^ unhex(dhash)) as hd from `photos` having hd > 0

        // updating the photo details returned by lambda function
        $photo->update($photoDetails);
    }
}
