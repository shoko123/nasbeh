<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarouselRequest;
use App\Http\Requests\PageRequest;
use App\Http\Requests\ShowRequest;
use App\Http\Resources\LocusPageTabularResourceCollection;
use App\Http\Resources\PotteryPageTabularResourceCollection;
use App\Http\Resources\StonePageTabularResourceCollection;
use App\Models\App\DigModel;
use Illuminate\Http\Request;

class DigModelReadController extends Controller
{
    protected $model_name = null;

    protected $model = null;

    public function index(Request $r, DigModel $m)
    {
        $collection = $m->index($r['query']);

        return response()->json($collection, 200);
    }

    public function page(PageRequest $r, DigModel $m)
    {
        $validated = $r->validated();

        if ($validated['view'] === 'Tabular') {
            $page = $m->page($validated['ids'], $validated['view']);

            switch ($r['model']) {
                case 'Locus':
                    return new LocusPageTabularResourceCollection($page);
                case 'Pottery':
                    return new PotteryPageTabularResourceCollection($page);
                case 'Stone':
                    return new StonePageTabularResourceCollection($page);
            }
        } else {
            return response()->json($m->page($validated['ids'], $validated['view']), 200);
        }
    }

    public function show(ShowRequest $sr, DigModel $m)
    {
        $v = $sr->validated();

        return response()->json($m->show($v), 200);
    }

    public function carousel(CarouselRequest $cr, DigModel $m)
    {
        $validated = $cr->validated();

        return response()->json($m->carousel($validated['id']), 200);
    }
}
