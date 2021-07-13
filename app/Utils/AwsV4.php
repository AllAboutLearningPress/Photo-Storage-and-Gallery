<?php

namespace App\Utils;

/**Thanks to https://gist.github.com/borislav-angelov/b9ba5ce727d03f2be6ef6d9abf23667f */
class AwsV4
{

    private $accessKeyID = null;
    private $secretAccessKey = null;
    private $regionName = null;
    private $serviceName = null;
    private $httpMethodName = null;
    private $canonicalURI = "/";
    private $queryParametes = array();
    private $awsHeaders = array();
    private $payload = "";

    /* Other variables */
    private $HMACAlgorithm = "AWS4-HMAC-SHA256";
    private $aws4Request = "aws4_request";
    private $strSignedHeader = null;
    private $xAmzDate = null;
    private $currentDate = null;

    public function __construct($creds)
    {
        $this->accessKeyID = $creds->key;
        $this->secretAccessKey = $creds->secret;
        $this->regionName = 'ap-southeast-1';
        $this->serviceName = 's3';
        $this->httpMethodName = 'GET';
        $this->canonicalURI = '';
        $this->queryParametes = [];
        $this->awsHeaders = [];
        $this->payload = '';

        /* Get current timestamp value.(UTC) */
        $this->xAmzDate = $this->getTimeStamp();
        $this->currentDate = $this->getDate();
    }

    /**
     * Task 1: Create a Canonical Request for Signature Version 4.
     *
     * @return
     */
    private function prepareCanonicalRequest()
    {
        $canonicalURL = "";

        /* Step 1.1 Start with the HTTP request method (GET, PUT, POST, etc.), followed by a newline character. */
        $canonicalURL .= $this->httpMethodName . "\n";

        /* Step 1.2 Add the canonical URI parameter, followed by a newline character. */
        $canonicalURL .= $this->canonicalURI . "\n";

        /* Step 1.3 Add the canonical query string, followed by a newline character. */
        $canonicalURL .= http_build_query($this->queryParametes) . "\n";

        /* Step 1.4 Add the canonical headers, followed by a newline character. */
        $signedHeaders = '';
        foreach ($this->awsHeaders as $key => $value) {
            $signedHeaders .= $key . ";";
            $canonicalURL .= $key . ":" . $value . "\n";
        }

        $canonicalURL .= "\n";

        /* Step 1.5 Add the signed headers, followed by a newline character. */
        $this->strSignedHeader = substr($signedHeaders, 0, -1);
        $canonicalURL .= $this->strSignedHeader . "\n";

        /* Step 1.6 Use a hash (digest) function like SHA256 to create a hashed value from the payload in the body of the HTTP or HTTPS. */
        $canonicalURL .= $this->generateHex($this->payload);

        return $canonicalURL;
    }

    /**
     * Task 2: Create a String to Sign for Signature Version 4.
     *
     * @param canonicalURL
     * @return
     */
    private function prepareStringToSign($canonicalURL)
    {
        $stringToSign = '';

        /* Step 2.1 Start with the algorithm designation, followed by a newline character. */
        $stringToSign .= $this->HMACAlgorithm . "\n";

        /* Step 2.2 Append the request date value, followed by a newline character. */
        $stringToSign .= $this->xAmzDate . "\n";

        /* Step 2.3 Append the credential scope value, followed by a newline character. */
        $stringToSign .= $this->currentDate . "/" . $this->regionName . "/" . $this->serviceName . "/" . $this->aws4Request . "\n";

        /* Step 2.4 Append the hash of the canonical request that you created in Task 1: Create a Canonical Request for Signature Version 4. */
        $stringToSign .= $this->generateHex($canonicalURL);

        return $stringToSign;
    }

    /**
     * Task 3: Calculate the AWS Signature Version 4.
     *
     * @param stringToSign
     * @return
     */
    private function calculateSignature($stringToSign)
    {
        /* Step 3.1 Derive your signing key */
        $signatureKey = $this->getSignatureKey($this->secretAccessKey, $this->currentDate, $this->regionName, $this->serviceName);

        /* Step 3.2 Calculate the signature. */
        $signature = hash_hmac("sha256", $stringToSign, $signatureKey, true);

        /* Step 3.2.1 Encode signature (byte[]) to Hex */
        $strHexSignature = strtolower(bin2hex($signature));

        return $strHexSignature;
    }

    /**
     * Task 4: Add the Signing Information to the Request. We'll return Map of
     * all headers put this headers in your request.
     *
     * @return
     */
    public function getHeaders()
    {
        $this->awsHeaders['x-amz-date'] = $this->xAmzDate;

        /* Execute Task 1: Create a Canonical Request for Signature Version 4. */
        $canonicalURL = $this->prepareCanonicalRequest();

        /* Execute Task 2: Create a String to Sign for Signature Version 4. */
        $stringToSign = $this->prepareStringToSign($canonicalURL);

        /* Execute Task 3: Calculate the AWS Signature Version 4. */
        $signature = $this->calculateSignature($stringToSign);
        if ($signature) {
            return array(
                'x-amz-date' => $this->xAmzDate,
                'Authorization' => $this->buildAuthorizationString($signature),
            );
        }
    }

    /**
     * Build string for Authorization header.
     *
     * @param strSignature
     * @return
     */
    private function buildAuthorizationString($strSignature)
    {
        return $this->HMACAlgorithm . " "
            . "Credential=" . $this->accessKeyID . "/" . $this->getDate() . "/" . $this->regionName . "/" . $this->serviceName . "/" . $this->aws4Request . ","
            . "SignedHeaders=" . $this->strSignedHeader . ","
            . "Signature=" . $strSignature;
    }

    /**
     * Generate Hex code of String.
     *
     * @param data
     * @return
     */
    private function generateHex($data)
    {
        return strtolower(bin2hex(hash("sha256", $data, true)));
    }

    /**
     * Generate AWS signature key.
     *
     * @param key
     * @param date
     * @param regionName
     * @param serviceName
     * @return
     * @throws Exception
     * @reference
     * http://docs.aws.amazon.com/general/latest/gr/signature-v4-examples.html#signature-v4-examples-java
     */
    private function getSignatureKey($key, $date, $regionName, $serviceName)
    {
        $kSecret = "AWS4" . $key;
        $kDate = hash_hmac("sha256", $date, $kSecret, true);
        $kRegion = hash_hmac("sha256", $regionName, $kDate, true);
        $kService = hash_hmac("sha256", $serviceName, $kRegion, true);
        $kSigning = hash_hmac("sha256", $this->aws4Request, $kService, true);

        return $kSigning;
    }

    /**
     * Get timestamp. yyyyMMdd'T'HHmmss'Z'
     *
     * @return
     */
    private function getTimeStamp()
    {
        return gmdate("Ymd\THis\Z");
    }

    /**
     * Get date. yyyyMMdd
     *
     * @return
     */
    private function getDate()
    {
        return gmdate("Ymd");
    }
}
