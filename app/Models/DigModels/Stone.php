<?php

namespace App\Models\DigModels;

use App\Models\App\DigModel;
use App\Models\Lookups\StoneBaseType;
use App\Models\Lookups\StoneCataloger;
use App\Models\Lookups\StoneMaterial;
use App\Models\Tags\StoneTag;
use App\Models\Tags\Tag;

class Stone extends DigModel
{
    protected $guarded = [];
    //protected $table = 'stones';

    public function __construct()
    {
        DigModel::__construct('Stone');
    }

    public function model_tags()
    {
        return $this->belongsToMany(StoneTag::class, 'stone-stone_tags', 'item_id', 'tag_id');
    }

    public function global_tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function baseType()
    {
        return $this->belongsTo(StoneBaseType::class, 'base_type_id');
    }

    public function material()
    {
        return $this->belongsTo(StoneMaterial::class, 'material_id');
    }

    public function cataloger()
    {
        return $this->belongsTo(StoneCataloger::class, 'cataloger_id');
    }

    public function getShortAttribute()
    {
        return is_null($this->description) ? 'TBD' : $this->description;
    }

    public function initInfo(): array
    {
        return [
            'display_options' => [
                'item_views' => ['Main', 'Media', 'Related'],
                'main_collection_views' => ['Gallery', 'Tabular', 'Chips'],
                'related_collection_views' => ['Gallery', 'Tabular', 'Chips'],
            ],
            'welcome_text' => 'Sources:
            Nasbeh.....',
        ];
    }

    public function builderIndexLoad(): void
    {
        $this->builder = $this->select('id');
    }

    public function builderIndexDefaultOrder(): void
    {
        //$this->builder->orderBy('id', 'asc');
    }

    public function builderPageTabularLoad(): void
    {
        $this->builder = $this->select('*');
    }

    public function builderPageGalleryLoad(): void
    {
        //load fields required by 'short' attribute
        $this->builder = $this->select('id', 'description')
            ->with(['media' => function ($query) {
                $query->select('*')->orderBy('order_column');
            }]);
    }

    public function builderShowLoad(): void
    {
        $this->builder = self::with([
            'media' => function ($query) {
                $query->orderBy('order_column');
            },
            'model_tags.tag_group',
            'global_tags.tag_group',
        ])->select('stones.*');
    }

    public function builderShowPostLoad(object $item): array
    {
        return [];
    }

    public function builderCarouselLoad(): void
    {
        $this->builder = $this->select('id', 'description')->with(['media' => function ($query) {
            $query->orderBy('order_column');
        }]);
    }
}
