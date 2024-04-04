<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Functional\MediaModel;

class AppController extends Controller
{
    public function __construct()
    {
    }

    public function init()
    {
        return response()->json([
            'appUrl' => env('APP_URL'),
            'bucketUrl' => env('S3_BUCKET_URL'),
            'accessibility' => [
                'readOnly' => env('ACCESSIBILITY_READ_ONLY'),
                'authenticatedUsersOnly' => env('ACCESSIBILITY_AUTHENTICATED_ONLY'),
            ],
            'media_collections' => MediaModel::media_collections(),
            'msg' => 'AppController.init()',
        ], 200);
    }
}
