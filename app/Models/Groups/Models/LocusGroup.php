<?php

namespace App\Models\Groups\Models;

use App\Models\Groups\ModelGroup;
use App\Models\Interfaces\ModelGroupInterface;

//require_once 'App\Models\Groups\global_tag_groups.php';
require_once app_path().'/Models/Groups/global_tag_groups.php';

class LocusGroup extends ModelGroup implements ModelGroupInterface
{
    private static $model_groups = [

        'Basic Typology' => [
            'group_type_code' => 'CR',
            'table_name' => 'loci',
            'column_name' => 'category',
        ],

        'Search-ID' => [
            'group_type_code' => 'CS',
            'column_name' => 'id',
        ],
        'Search-OC-Label' => [
            'group_type_code' => 'CS',
            'column_name' => 'oc_label',
        ],
        'Order By' => [
            'group_type_code' => 'OB',
            'params' => [
                ['name' => 'Basic Typology', 'column_name' => 'category'],
                ['name' => 'Number', 'column_name' => 'a'],
                ['name' => 'Subnumber', 'column_name' => 'b'],
                ['name' => 'Publication Date', 'column_name' => 'published_date'],
            ],
        ],
    ];

    public function __construct()
    {
        ModelGroup::__construct('Locus');
        self::$groups = array_merge(self::$model_groups, globalGroups());
    }

    public static function getModelGroups(): array
    {
        return array_merge(globalGroups(), self::$groups);
    }

    public function trio(): array
    {
        $cats = [
            'Search' => [
                'Search-ID',
                'Search-OC-Label',

            ],
            'Registration' => [
                'Media',
            ],

            'Basic Characteristics' => [
                'Basic Typology',
            ],
            'Order By' => [
                'Order By',
            ],
        ];

        return $this->buildTrio($cats);
    }
}
