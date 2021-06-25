<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Provider
    |--------------------------------------------------------------------------
    |
    | This option controls whether to create the Elasticesarch client with v4
    | signing for AWS or just a normal client.
    | 
    | Supported: "elasticsearch", "aws"
    |
    */

    'provider' => env('ELASTICSEARCH_PROVIDER', 'elasticsearch'),

    /*
    |--------------------------------------------------------------------------
    | Region
    |--------------------------------------------------------------------------
    |
    | Ignored if provider is elasticsearch
    |
    | Put your AWS region in here. Sure, could poll the metadata service on
    | each call, but that seems like a lot of unnecessary overhead. So put it
    | here to override .env values.
    |
    */

    'region' => env('AWS_REGION', 'us-west-2'),

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | Ignored if provider is elasticsearch
    |
    | Put your AWS IAM credentials in here.
    |
    */

    'credentials' => [
        'key' => env('AWS_ACCESS_KEY'),
        'secret' => env('AWS_ACCESS_SECRET'),
        'token' => env('AWS_ACCESS_TOKEN'),
    ],

];
