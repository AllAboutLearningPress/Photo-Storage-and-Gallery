<?php

namespace App\Utils;

class AwsS3V4
{

    private $HMACAlgorithm = "AWS4-HMAC-SHA256";

    public function __construct($expires = 21600)
    {
        $this->region = config('services.ses.region');
        $this->httpMethodName = 'GET';
        $this->canonicalURI = '';

        $this->awsHeaders = [
            "Host" => "aalpphotosdev.s3.ap-southeast-1.amazonaws.com"
        ];

        $provider = \Aws\Credentials\CredentialProvider::defaultProvider();
        $creds = $provider()->wait();

        $this->key_id = $creds->getAccessKeyId();
        $this->secret_key = $creds->getSecretKey();
        $token = $creds->getSecurityToken();
        $currentTime = time();
        $this->date_text = gmdate('Ymd', $currentTime);
        $this->time_text = $this->date_text . 'T' . gmdate('His', $currentTime) . 'Z';
        $this->scope = $this->date_text . "/" . $this->region . "/s3/aws4_request";
        $date_text = gmdate('Ymd', time());
        $time_text = $date_text . 'T' . gmdate('His', time()) . 'Z';
        $this->scope = $date_text . "/" . $this->region . "/s3/aws4_request";

        $x_amz_params = array(
            'X-Amz-Algorithm' => $this->HMACAlgorithm,
            'X-Amz-Credential' => $this->key_id . '/' . $this->scope,
            'X-Amz-Date' => $time_text,
            'X-Amz-Expires' => $expires, // 'Expires' is the number of seconds until the request becomes invalid
            'X-Amz-SignedHeaders' => 'host',
        );
        if ($token) {
            $x_amz_params['X-Amz-Security-Token'] = $token;
        }
        // sorting the params in alphabatical order
        ksort($x_amz_params);
        $this->query_string = "";
        foreach ($x_amz_params  as $key => $value) {
            $this->query_string .= rawurlencode($key) . '=' . rawurlencode($value) . "&";
        }

        $this->query_string = substr($this->query_string, 0, -1);
    }


    public function presignGet(
        $encoded_uri,
        $bucket
    ) {


        // Specify the hostname for the S3 endpoint
        if ($this->region == 'us-east-1') {
            $hostname = trim($bucket . ".s3.amazonaws.com");
            $header_string = "host:" . $hostname . "\n";
        } else {
            $hostname =  trim($bucket . ".s3-" . $this->region . ".amazonaws.com");
            $header_string = "host:" . $hostname . "\n";
        }
        $signed_headers_string = "host";




        // Task 1: Creating canonical request
        $canonical_request = "GET\n" . $encoded_uri . "\n" . $this->query_string . "\n" . $header_string . "\n" . $signed_headers_string . "\nUNSIGNED-PAYLOAD";

        // Task 2: Creating a string to sign
        $string_to_sign = $this->HMACAlgorithm . "\n" . $this->time_text . "\n" . $this->scope . "\n" . hash('sha256', $canonical_request, false);

        $signing_key = hash_hmac('sha256', 'aws4_request', hash_hmac('sha256', 's3', hash_hmac('sha256', $this->region, hash_hmac('sha256', $this->date_text, 'AWS4' . $this->secret_key, true), true), true), true);

        // Task 3: Generating the signature
        $signature = hash_hmac('sha256', $string_to_sign, $signing_key);

        // Task 4: Adding the signature and query string to the url
        return 'https://' . $hostname . $encoded_uri . '?' . $this->query_string . '&X-Amz-Signature=' . $signature;
    }
}