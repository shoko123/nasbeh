<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagSyncRequest;
use App\Models\App\DigModel;
use App\Models\Functional\TagModel;

class TagController extends Controller
{
    public function sync(TagSyncRequest $r, DigModel $m, TagModel $tagModel)
    {
        $validated = $r->validated();

        return response()->json($tagModel->sync($validated, $m), 200);
    }
}
