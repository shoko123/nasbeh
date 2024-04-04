<?php

namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Model;

class PotteryTagGroup extends Model
{
    public $timestamps = false;

    protected $table = 'pottery_tag_groups';

    public function tags()
    {
        return $this->hasMany(PotteryTag::class, 'group_id', 'id');
    }
}
