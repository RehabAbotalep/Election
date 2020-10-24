<?php
/**
 * @see https://github.com/Edujugon/PushNotification
 */

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAk4fPbmU:APA91bGIUdDKOq9G82tdqQiSI28XaB6no7V1P09apYhb1_KkTjesXUs2hEKEZuPkR5H83A9o4K6fB_w2gS9lOe20WIQjn4htTVI-L6hxBWIevTxpMReajyxG7P0CqDq76EnXTKNCgMxz',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
        'passPhrase' => 'secret', //Optional
        'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => true,
    ],
];
