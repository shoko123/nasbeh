<?php

namespace App\Models\DigModels;

use App\Models\App\DigModel;
use App\Models\Tags\PotteryTag;
use App\Models\Tags\Tag;

class Locus extends DigModel
{
    protected $table = 'loci';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];

    protected $appends = ['short'];

    public function __construct()
    {
        DigModel::__construct('Locus', []);
    }

    // public function model_tags()
    // {
    //     return $this->belongsToMany(PotteryTag::class, 'pottery-pottery_tags', 'item_id', 'tag_id');
    // }

    // public function global_tags()
    // {
    //     return $this->morphToMany(Tag::class, 'taggable');
    // }

    public function initInfo(): array
    {
        return [
            'display_options' => [
                'item_views' => ['Main', 'Media', 'Related'],
                'main_collection_views' => ['Gallery', 'Tabular', 'Chips'],
                'related_collection_views' => ['Gallery', 'Tabular', 'Chips'],
            ],
            'welcome_text' => 'Loci as drived from opencontexts` "features". Most have no information except a label.
            Attempts were made to clean and streamline inconsistencies (e.g. "Blank" labels were changes to "Unknown"; Duplicates were given an extra numerical index).',
        ];
    }

    public function getShortAttribute()
    {
        // $short = is_null($this->type) ? "" : 'Type: ' . $this->type . '. ';
        // $short .= empty($this->cross_ref) ? "" : 'Ref: ' . $this->cross_ref;
        return $this->oc_label; //$short;
    }

    public function builderIndexLoad(): void
    {
        $this->builder = $this->select('id');
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
        $this->builder = $this->select('*')
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
        ]);
    }

    public function builderShowPostLoad(object $item): array
    {
        return []; //$this->relatedFinds($item);
    }

    protected function relatedFinds(object $item): array
    {
        $related = [];

        return $related;
    }

    public function builderCarouselLoad(): void
    {
        $this->builder = $this->select('id')->with(['media' => function ($query) {
            $query->orderBy('order_column');
        }]);
    }
}
