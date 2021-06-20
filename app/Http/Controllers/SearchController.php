<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\Lambda\LambdaClient;
use Aws\Credentials\Credentials;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->validate(['search' => 'required|string']);
        $credentials = new Credentials(config('services.ses.key'), config('services.ses.secret'));
        $client = new LambdaClient(array(
            'credentials' => $credentials,
            'region' => config('services.ses.region'),
            'version' => 'latest'
        ));
        $result = $client->invoke(array(
            // FunctionName is required
            'FunctionName' => 'arn:aws:lambda:us-east-1:728758055541:function:photo_storage_elastic_search',
            'InvocationType' => 'RequestResponse',
            'LogType' => 'None',
            //'ClientContext' => 'string',
            'Payload' => json_encode(array(
                'search' => $data['search'],
                'score' => isset($data['score']) ? $data['score'] : "90"
            )),
            //'Qualifier' => 'string',
        ));
        dd($result['Payload']->getContents());
    }
}
