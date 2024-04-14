<?php

namespace App\Models\App;

use App\Models\Functional\MediaModel;
use App\Models\Interfaces\DigModelInterface;
use App\Models\Tags\Tag;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

abstract class DigModel extends Model implements DigModelInterface, HasMedia
{
    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];

    protected $eloquent_model_name = '';

    protected $builder;

    protected $date_columns = [];

    use InteractsWithMedia;

    public function __construct($eloquent_model_name, $date_columns)
    {
        $this->eloquent_model_name = $eloquent_model_name;
        $this->date_columns = $date_columns;
    }

    public function eloquentName()
    {
        return $this->eloquent_model_name;
    }
    public function dateColumns()
    {
        return $this->date_columns;
    }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('tn')
            ->width(250)
            ->height(250)
            ->sharpen(10)
            ->nonQueued();
    }

    public function index($query)
    {
        $this->builder = $this->select('id');
        //$this->builderIndexLoad();
        $this->builderIndexApplyFilters($query);

        if (empty($query['order_by'])) {
            $this->builderIndexDefaultOrder();
        } else {
            $this->builderIndexOrder($query['order_by']);
        }

        $collection = $this->builder->get();

        //return $collection;
        return $collection->map(function ($item, $key) {
            return $item->id;
        });
    }

    public function builderIndexApplyFilters($query)
    {
        if (!empty($query['model_tag_ids'])) {
            $this->applyModelTagFilters($query['model_tag_ids']);
        }

        if (!empty($query['global_tag_ids'])) {
            $this->applyGlobalTagFilters($query['global_tag_ids']);
        }

        if (!empty($query['column_lookup_ids'])) {
            $this->applyColumnLookupOrValueFilters($query['column_lookup_ids']);
        }

        if (!empty($query['column_values'])) {
            $this->applyColumnLookupOrValueFilters($query['column_values']);
        }

        if (!empty($query['column_search'])) {
            $this->applyColumnSearchFilters($query['column_search']);
        }

        if (!empty($query['media'])) {
            $this->applyMediaFilter($query['media']);
        }
    }

    public function applyModelTagFilters(array $tag_ids)
    {
        $modelName = 'App\\Models\\Tags\\' . $this->eloquent_model_name . 'Tag';
        $model = new $modelName;
        $groups = [];
        $tags = $model->select('id', 'group_id')->whereIn('id', $tag_ids)->get();

        foreach ($tags as $tag) {
            if (array_key_exists($tag->group_id, $groups)) {
                array_push($groups[$tag->group_id], $tag->id);
            } else {
                $groups[$tag->group_id] = [$tag->id];
            }
        }

        foreach ($groups as $type_id => $tag_ids_for_group) {
            $this->builder->whereHas('model_tags', function (Builder $q) use ($tag_ids_for_group) {
                $q->whereIn('id', $tag_ids_for_group);
            });
        }
    }

    public function applyGlobalTagFilters(array $tag_ids)
    {
        $tags = Tag::select('id', 'group_id')->whereIn('id', $tag_ids)->get();
        $groups = [];

        foreach ($tags as $tag) {
            if (array_key_exists($tag->group_id, $groups)) {
                array_push($groups[$tag->group_id], $tag->id);
            } else {
                $groups[$tag->group_id] = [$tag->id];
            }
        }

        foreach ($groups as $type_id => $tag_ids_for_group) {
            $this->builder->whereHas('global_tags', function (Builder $q) use ($tag_ids_for_group) {
                $q->whereIn('id', $tag_ids_for_group);
            });
        }
    }

    public function applyColumnLookupOrValueFilters(array $cols)
    {
        foreach ($cols as $key => $col) {
            $this->builder->whereIn($col['column_name'], $col['vals']);
        }
    }

    public function applyColumnSearchFilters(array $cols)
    {
        foreach ($cols as $key => $col) {
            $this->builder->Where(function ($query) use ($col) {
                foreach ($col['vals'] as $key1 => $term) {
                    $query->orWhere($col['column_name'], 'LIKE', '%' . $term . '%');
                }
            });
        }
    }

    public function applyBespokeFilters(array $bespoke)
    {
        foreach ($bespoke as $key => $item) {
            switch ($item['name']) {
                case 'Media':
                    $this->applyMediaFilter($item['vals']);
                    break;
                default:
            }
        }
    }

    public function applyMediaFilter(array $collectionNames)
    {
        $this->builder->whereHas('media', function (Builder $mediaQuery) use ($collectionNames) {
            $mediaQuery->whereIn('collection_name', $collectionNames);
        });
    }

    public function page($ids, $view): Collection
    {
        switch ($view) {
            case 'Tabular':
                $this->builderPageTabularLoad();
                break;

            case 'Gallery':
                $this->builderPageGalleryLoad();
                break;
        }
        $this->builder = $this->builder->whereIn('id', $ids);

        //order by given (string) ids
        $sortedIds = "'" . implode("', '", $ids) . "'";

        $res = $this->builder->orderByRaw("FIELD(id, {$sortedIds})")
            ->get();

        switch ($view) {
            case 'Tabular':
                return $res;

            case 'Gallery':
                $r = collect([]);
                $r = $res->map(function ($item, $key) {
                    $media = null;
                    if (!$item->media->isEmpty()) {
                        $media = ['full' => $item->media[0]->getPath(), 'tn' => $item->media[0]->getPath('tn')];
                    }

                    return [
                        'id' => $item['id'],
                        'short' => $item['short'],
                        'media' => $media,
                    ];
                });

                return $r;
        }
    }

    public function builderIndexOrder(array $order_by)
    {
        foreach ($order_by as $key => $data) {
            $this->builder->orderBy($data['column_name'], $data['asc'] ? 'asc' : 'desc');
        }
    }

    public function show(array $validated)
    {
        $this->builderShowLoad();
        $item = $this->builder->findOrFail($validated['id']);
        $related = $this->builderShowPostLoad($item);

        $mediaArray = MediaModel::getUrlsOfAll($item->media);

        //model tags (discrete)
        $model_tags = isset($item['model_tags']) ? $item->model_tags->map(function ($tag, int $key) {
            return ['group_label' => $tag->tag_group->name, 'tag_text' => $tag->name];
        }) : [];

        //global tags
        $global_tags = isset($item['global_tags']) ? $item->global_tags->map(function ($tag, int $key) {
            return ['group_label' => $tag->tag_group->name, 'tag_text' => $tag->name];
        }) : [];

        //unset
        unset($item->media);
        unset($item->global_tags);
        unset($item->model_tags);

        return [
            'fields' => $item->makeHidden(['short']),
            'media' => $mediaArray,
            'global_tags' => $global_tags,
            'model_tags' => $model_tags,
            'short' => $item->short,
            'related' => $related,
        ];
    }

    public function carousel(string $id)
    {
        $this->builderCarouselLoad();
        $item = $this->builder->findOrFail($id);

        return [
            'id' => $id,
            'short' => $item['short'],
            'urls' => count($item->media) === 0 ? null : MediaModel::getUrlsOfOne($item->media),
            'module' => $this->eloquent_model_name,
        ];
    }

    public function destroyItem(string $id)
    {
        $item = self::with(['model_tags', 'global_tags'])->findOrFail($id);
        DB::transaction(function () use ($item) {
            $item->model_tags()->detach();
            $item->global_tags()->detach();
            $item->delete();
        });
        unset($item->model_tags);
        unset($item->global_tags);

        return $item;
    }

    public function store(array $new_item, bool $methodIsPost)
    {
        if ($methodIsPost) {
            $modelName = "App\Models\DigModels\\" . $this->eloquent_model_name;
            $item = new $modelName;
        } else {
            $item = self::findOrFail($new_item['id']);
        }

        //copy the validated data from the validated array to the 'item' object.
        foreach ($new_item as $key => $value) {
            $item[$key] = $value;
        }

        if ($methodIsPost) {
            unset($item->id);
        }

        try {
            $item->save();
        } catch (Exception $error) {
            throw new Exception('Error while saving item to DB: ' . $error);
        }

        if ($methodIsPost) {
            return [
                'fields' => $item->makeHidden(['short']),
                'media' => [],
                'global_tags' => [],
                'model_tags' => [],
                'short' => $item->short,
            ];
        } else {
            return [
                'fields' => $item->makeHidden(['short']),
                'short' => $item->short,
            ];
        }
    }
}
