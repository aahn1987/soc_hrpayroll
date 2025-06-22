<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging (FCM) Configuration
    |--------------------------------------------------------------------------
    |
    | This file stores configuration related to FCM, including path to the
    | service account JSON file and the Firebase project ID.
    |
    */

    'service_account_json' => env('FCM_SERVICE_ACCOUNT_JSON', 'storage/firebase/sdk.json'),

    'project_id' => env('FCM_PROJECT_ID', 'your-project-id'),
];
