<?php

namespace App\Jobs;

use App\Models\Label;
use App\Models\Photo;
use Aws\Lambda\LambdaClient;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $photo = Photo::where('id', "=", $this->photoId)->select('id', 'file_name')->first();

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
                        'Name' => 'fullsize/' . $photo->file_name
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
    }
}
