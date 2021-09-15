<?php

use App\Utils\AwsS3V4;
use Illuminate\Support\Collection;

if (!function_exists('genTempSrc')) {
    function genTempSrc($photos, $file_version)
    {
        $awsS3V4 = new AwsS3V4();
        $bucket = config('aws.fullsize_bucket');

        foreach ($photos as $photo) {
            $full_path = $photo->genFullPath($file_version);
            $photo->src = Cache::remember($full_path, 600, function () use ($awsS3V4, $full_path, $bucket) {
                return  $awsS3V4->presignGet($full_path, $bucket);
            });
        }
        return $photos;
    }
}
