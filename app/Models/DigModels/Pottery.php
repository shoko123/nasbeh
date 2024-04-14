<?php

namespace App\Models\DigModels;

use App\Models\App\DigModel;
use App\Models\Tags\PotteryTag;
use App\Models\Tags\Tag;

class Pottery extends DigModel
{
    public $timestamps = false;

    protected $guarded = [];

    protected $table = 'loci';

    protected $appends = ['short'];

    public function __construct()
    {
        DigModel::__construct('Pottery', []);
    }

    public function model_tags()
    {
        return $this->belongsToMany(PotteryTag::class, 'pottery-pottery_tags', 'item_id', 'tag_id');
    }

    public function global_tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
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
            Hazor VII: The 1990-2012 Excavations, The Bronze Age. 2017. A. Ben-Tor, S. Zuckerman, S. Bechar, and D. Sandhaus, eds. Jerusalem. 
            Hazor VI: The 1990-2009 Excavations, The Iron Age. 2012. A. Ben-Tor, D. Ben-Ami, and D. Sandhaus, eds. Jerusalem.',
        ];
    }

    public function getShortAttribute()
    {
        $short = is_null($this->type) ? '' : 'Type: ' . $this->type . '. ';
        $short .= empty($this->cross_ref) ? '' : 'Ref: ' . $this->cross_ref;

        return $short;
    }

    public function builderIndexLoad(): void
    {
        $this->builder = $this->select('id', 'area', 'name');
    }

    public function builderIndexDefaultOrder(): void
    {
        $this->builder->orderBy('id', 'asc');
    }

    public function builderPageTabularLoad(): void
    {
        $this->builder = $this->select('*');
    }

    public function builderPageGalleryLoad(): void
    {
        $this->builder = $this->select('id', 'area', 'name', 'type', 'cross_ref')
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
        ]);
    }

    public function builderShowPostLoad(object $item): array
    {
        return $this->relatedFinds($item);
    }

    protected function relatedFinds(object $item): array
    {
        $related = [];

        return $related;
    }

    public function builderCarouselLoad(): void
    {
        $this->builder = $this->select('id', 'area', 'name', 'type', 'cross_ref')->with(['media' => function ($query) {
            $query->orderBy('order_column');
        }]);
    }
}
