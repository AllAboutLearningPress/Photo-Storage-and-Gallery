<?php

namespace App\Utils;

use App;
use Aws\Sdk;
use Aws\Credentials\Credentials;
use Aws\Credentials\CredentialProvider;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;

class AwsS3V4
{

    private $HMACAlgorithm = "AWS4-HMAC-SHA256";
    const SERVER_URI = 'http://169.254.169.254/latest/';
    const CRED_PATH = 'meta-data/iam/security-credentials/';
    const TOKEN_PATH = 'api/token';

    const ENV_DISABLE = 'AWS_EC2_METADATA_DISABLED';
    const ENV_TIMEOUT = 'AWS_METADATA_SERVICE_TIMEOUT';
    const ENV_RETRIES = 'AWS_METADATA_SERVICE_NUM_ATTEMPTS';

    /** @var string */
    private $profile;

    /** @var object */
    private $client;

    /** @var int */
    private $retries;

    /** @var int */
    private $attempts;

    /** @var float|mixed */
    private $timeout;

    /** @var bool */
    private $secureMode = true;

    public function __construct($expires = 21600)
    {
        $this->start = microtime(true);
        $this->region = config('services.ses.region');
        $this->bucket = config('aws.fullsize_bucket');
        $this->httpMethodName = 'GET';
        $this->canonicalURI = '';
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://169.254.169.254',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);


        if (App::environment('production')) {
            // This environment is in production
            $creds = $this->getCreds();
            //dd($creds);
        } else {

            $memorized_provider = CredentialProvider::memoize(CredentialProvider::defaultProvider());
            $creds = $memorized_provider()->wait();
        }



        //dd($creds, microtime(true) - $this->start);
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

        $this->timeout = (float) getenv(self::ENV_TIMEOUT) ?: (isset($config['timeout']) ? $config['timeout'] : 1.0);
        $this->profile = isset($config['profile']) ? $config['profile'] : null;
        $this->retries = (int) getenv(self::ENV_RETRIES) ?: (isset($config['retries']) ? $config['retries'] : 3);
        $this->attempts = 0;
        $this->client = isset($config['client'])
            ? $config['client'] // internal use only
            : \Aws\default_http_handler();
    }
    // public function setHeaders()
    // {
    //     $this->awsHeaders = [
    //         "Host" => $this->bucket . ".s3." . $this->region . ".amazonaws.com"
    //     ];
    // }
    public function getCreds()
    {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "http://169.254.169.254/latest/meta-data/identity-credentials/ec2/security-credentials/ec2-instance/");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $creds_json = json_decode($output);

        return new Credentials(
            $creds_json->AccessKeyId,
            $creds_json->SecretAccessKey,
            $creds_json->Token
        );
    }
    /**
     * Presigns Get request to AWS S3
     *
     * We are using the therm directory here. But s3 doesnt have directories. So
     * Its here to be relatable with normal file system.
     *
     * @param string $dir The directory of the file.
     * @param string $file_name The file name of the file we are signing request for.
     * @param string $bucket The bucket this file is stored on.
     *
     * @return string Signed url of the reqeusted file.
     */
    public function presignGet(
        String $dir,
        String $file_name,
        String $bucket
    ) {
        # calculating full uri from directory and filename
        $encoded_uri = '/' . $dir . '/' . $file_name;

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



    public function request($url, $method = 'GET', $headers)
    {
        // retrive profile
        $request = new Request('GET', self::SERVER_URI . self::CRED_PATH);
        $userAgent = 'aws-sdk-php/' . Sdk::VERSION;
        $userAgent .= ' ' . \Aws\default_user_agent();
        $request = $request->withHeader('User-Agent', $userAgent);
        $request = $request->withHeader('User-Agent', $userAgent);

        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }
        $profile = ($this->client)->send($request)->getBody()->getContents();
    }
}
