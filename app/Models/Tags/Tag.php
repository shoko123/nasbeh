<?php

namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    protected $table = 'tags';

    public function tag_group()
    {
        return $this->belongsTo(TagGroup::class, 'group_id');
    }

    public function pottery()
    {
        return $this->morphedByMany('Pottery', 'taggable');
    }

    public function stones()
    {
        return $this->morphedByMany('Stone', 'taggable');
    }
}
