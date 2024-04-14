<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\App\DigModel;
use App\Models\Groups\ModelGroup;
use Illuminate\Support\Facades\DB;

class DigModelInitController extends Controller
{
    public function init(DigModel $m, ModelGroup $mgi)
    {
        $counts = ['items' => $m->count(), 'media' => DB::table('media')->where('model_type', $m->eloquentName())->count()];
        $first_record = $m->select('id')->firstOrFail();

        return response()->json(array_merge(['counts' => $counts, 'first_id' => $first_record['id']], ['date_columns' => $m->dateColumns()], $m->initInfo(), $mgi->trio()), 200);
    }
}
