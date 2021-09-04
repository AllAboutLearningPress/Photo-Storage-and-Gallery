<?php

use App\Utils\AwsS3V4;
use Illuminate\Support\Collection;

if (!function_exists('genTempSrc')) {
    function genTempSrc($photos, $file_dir)
    {
        $awsS3V4 = new AwsS3V4();
        $bucket = config('aws.fullsize_bucket');

        foreach ($photos as $photo) {
            $photo->src = $awsS3V4->presignGet($file_dir, $photo->file_name, $bucket);
        }
        return $photos;
    }
}
