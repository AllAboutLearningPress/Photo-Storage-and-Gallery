<?php

return [

    'fullsize_bucket' => env('FULLSIZE_BUCKET', 'aalpphotos'),
    'preview_bucket' => env('PREVIEW_BUCKET', 'aalpphotos_preview'),
    'post_upload_arn' => env('PHOTO_POST_UPLOAD_LAMBDA_ARN')

];
