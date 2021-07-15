<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Utils\AwsV4;


class SearchController extends Controller
{
    public function searchTitle(Request $request)
    {
        $data = $request->validate(['search' => 'required|string']);
        $photos = Photo::search($data['search'])->get();


        // foreach ($photos as $photo) {
        //     $photo->add_temp_url('thumbnails');
        // }
        //return $photos->toArray();
        $s3Client = new \Aws\S3\S3Client([
            'region' => 'ap-southeast-1', // config('filesystems.disks.s3_fullsize.region'),
            'version' => '2006-03-01',
        ]);
        $provider = \Aws\Credentials\CredentialProvider::defaultProvider();
        $creds = $provider()->wait();
        // $creds->key,secret
        //\Aws\Signature\Signature
        $date_full = date('D, d M Y H:i:s \G\M\T'); // @todo don't force GMT!
        $date_time = date('Ymd\THisZ'); // @todo don't force Z!
        $date = date('Ymd');

        $key = $creds->getAccessKeyId();
        $secret = $creds->getSecretKey();
        for ($x = 0; $x < count($photos); $x++) {
            // $photos[$x]->add_temp_url('thumbnails');
            // continue;

            $photos[$x]->src = $this->AWS_S3_PresignDownload(
                $creds->getAccessKeyId(),
                $creds->getSecretKey(),
                $creds->getSecurityToken(),
                'aalpphotosdev',
                'ap-southeast-1',
                "/thumbnails" . "/" . $photos[$x]->file_name
            );
        }
        return $photos->toArray();
    }

    private function genCanonicalUri($file_path)
    {
        return 'https://' . 'aalpphotosdev' . "." . 'ap-southeast-1' . ".amazonaws.com/"
            . $file_path;
    }
    private function AWS_S3_PresignDownload(
        $AWSAccessKeyId,
        $AWSSecretAccessKey,
        $securityToken,
        $BucketName,
        $AWSRegion,
        $canonical_uri,
        $expires = 8400
    ) {

        $encoded_uri = $canonical_uri;
        // Specify the hostname for the S3 endpoint
        if ($AWSRegion == 'us-east-1') {
            $hostname = trim($BucketName . ".s3.amazonaws.com");
            $header_string = "host:" . $hostname . "\n";
        } else {
            $hostname =  trim($BucketName . ".s3-" . $AWSRegion . ".amazonaws.com");
            $header_string = "host:" . $hostname . "\n";
        }
        $signed_headers_string = "host";
        $currentTime = time();
        $date_text = gmdate('Ymd', $currentTime);

        $time_text = $date_text . 'T' . gmdate('His', $currentTime) . 'Z';
        $algorithm = 'AWS4-HMAC-SHA256';
        $scope = $date_text . "/" . $AWSRegion . "/s3/aws4_request";

        $x_amz_params = array(
            'X-Amz-Algorithm' => $algorithm,
            'X-Amz-Credential' => $AWSAccessKeyId . '/' . $scope,
            'X-Amz-Date' => $time_text,
            'X-Amz-Expires' => $expires, // 'Expires' is the number of seconds until the request becomes invalid
            'X-Amz-Security-Token' => $securityToken,
            'X-Amz-SignedHeaders' => $signed_headers_string,
        );

        // sorting the params in alphabatical order
        ksort($x_amz_params);

        $query_string = "";
        foreach ($x_amz_params as $key => $value) {
            $query_string .= rawurlencode($key) . '=' . rawurlencode($value) . "&";
        }

        $query_string = substr($query_string, 0, -1);

        $canonical_request = "GET\n" . $encoded_uri . "\n" . $query_string . "\n" . $header_string . "\n" . $signed_headers_string . "\nUNSIGNED-PAYLOAD";
        $string_to_sign = $algorithm . "\n" . $time_text . "\n" . $scope . "\n" . hash('sha256', $canonical_request, false);

        $signing_key = hash_hmac('sha256', 'aws4_request', hash_hmac('sha256', 's3', hash_hmac('sha256', $AWSRegion, hash_hmac('sha256', $date_text, 'AWS4' . $AWSSecretAccessKey, true), true), true), true);

        $signature = hash_hmac('sha256', $string_to_sign, $signing_key);
        return 'https://' . $hostname . $encoded_uri . '?' . $query_string . '&X-Amz-Signature=' . $signature;
    }
}
