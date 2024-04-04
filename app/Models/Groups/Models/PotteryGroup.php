<?php

namespace App\Models\Groups\Models;

use App\Models\Groups\ModelGroup;
use App\Models\Interfaces\ModelGroupInterface;

require_once app_path().'/Models/Groups/global_tag_groups.php';

class PotteryGroup extends ModelGroup implements ModelGroupInterface
{
    private static $model_groups = [
        'Area' => [
            'group_type_code' => 'CR',
            'table_name' => 'loci',
            'column_name' => 'area',
        ],
        'Stratum' => [
            'group_type_code' => 'TM',
            'dependency' => null,
            'multiple' => true,
        ],
        'Search-Name' => [
            'group_type_code' => 'CS',
            'column_name' => 'name',
        ],
        'Search-Description' => [
            'group_type_code' => 'CS',
            'column_name' => 'description',
        ],
        'Search-Cross-Reference' => [
            'group_type_code' => 'CS',
            'column_name' => 'cross_ref',
        ],
        'Search-Type' => [
            'group_type_code' => 'CS',
            'column_name' => 'type',
        ],
        'Search-Square' => [
            'group_type_code' => 'CS',
            'column_name' => 'square',
        ],
        'Search-Stratum' => [
            'group_type_code' => 'CS',
            'column_name' => 'stratum',
        ],
        'Order By' => [
            'group_type_code' => 'OB',
            'params' => [
                ['name' => 'Area', 'column_name' => 'area'],
                ['name' => 'Year', 'column_name' => 'year'],
            ],
        ],
    ];

    public function __construct()
    {
        ModelGroup::__construct('Pottery');
        self::$groups = array_merge(globalGroups(), self::$model_groups);
    }

    public static function getModelGroups(): array
    {
        return self::$groups;
    }

    public function trio(): array
    {
        $cats = [
            'Registration' => [
                'Area',
                'Stratum',
            ],
            'Search' => [
                'Search-Name',
                'Search-Description',
                'Search-Cross-Reference',
                'Search-Square',
                'Search-Stratum',
                'Search-Type',
            ],
            'Order By' => [
                'Order By',
            ],
        ];

        return $this->buildTrio($cats);
    }
}
