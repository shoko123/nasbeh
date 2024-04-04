<?php

namespace App\Models\Tags;

use App\Models\DigModels\Pottery;
use Illuminate\Database\Eloquent\Model;

class PotteryTag extends Model
{
    public $timestamps = false;

    protected $table = 'pottery_tags';

    public function tag_group()
    {
        return $this->belongsTo(PotteryTagGroup::class, 'group_id');
    }

    public function item()
    {
        return $this->belongsToMany(Pottery::class, 'pottery-pottery_tags', 'tag_id', 'item_id');
    }
}
