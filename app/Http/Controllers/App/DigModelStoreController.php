<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\DigModelStoreRequest;
use App\Models\App\DigModel;

class DigModelStoreController extends Controller
{
    public function store(DigModelStoreRequest $r, DigModel $m)
    {
        $validated = $r->validated();
        $item = $m->store($validated['item'], $r->isMethod('post'));

        $resp = array_merge($item, [
            'msg' => 'ModelControler.store(success)',
        ]);

        return response()->json($resp, 200);
    }
}
