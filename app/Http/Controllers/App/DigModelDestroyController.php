<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\App\DigModel;
use Illuminate\Http\Request;

class DigModelDestroyController extends Controller
{
    public function destroy(Request $r, DigModel $m)
    {
        return response()->json($m->destroyItem($r['id']), 200);
    }
}
