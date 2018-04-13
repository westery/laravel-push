<?php

return [
    'default' => env('PUSH_AGENT', 'Aliyun'),

    'agents' => [
        //阿里云推送
        'Aliyun' => [
            'accessKeyId' => env('Aliyun_Pusher_AK_ID','your app access key id'),
            'accessKeySecret' => env('Aliyun_Pusher_AK_SECRET','your app access key secret'),
            'appKey' => env('Aliyun_Pusher_APPKEY','your app app key'),
        ],
    ],
];