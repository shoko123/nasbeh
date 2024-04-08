<?php

namespace App\Models\Groups\Models;

use App\Models\Groups\ModelGroup;
use App\Models\Interfaces\ModelGroupInterface;

//require_once 'App\Models\Groups\global_tag_groups.php';
require_once app_path() . '/Models/Groups/global_tag_groups.php';

class StoneGroup extends ModelGroup implements ModelGroupInterface
{
    private static $model_groups = [

        'Material' => [
            'group_type_code' => 'CL',
            'dependency' => null,
            'table_name' => 'stone_materials',
            'column_name' => 'material_id',
        ],
        'Whole' => [
            'group_type_code' => 'CB',
            'column_name' => 'whole',
            'params' => ['Yes', 'No'],
        ],
        'Basic Typology' => [
            'group_type_code' => 'CL',
            'dependency' => null,
            'table_name' => 'stone_base_types',
            'column_name' => 'base_type_id',
        ],
        'Cataloger' => [
            'group_type_code' => 'CL',
            'dependency' => null,
            'table_name' => 'stone_catalogers',
            'column_name' => 'cataloger_id',
        ],
        'Life Stage' => [
            'group_type_code' => 'TM',
            'dependency' => null,
            'multiple' => true,
        ],
        'Morphology' => [
            'group_type_code' => 'TM',
            'dependency' => null,
            'multiple' => true,
        ],
        'Profile' => [
            'group_type_code' => 'TM',
            'dependency' => null,
            'multiple' => true,
        ],
        'Production' => [
            'group_type_code' => 'TM',
            'dependency' => null,
            'multiple' => true,
        ],
        'Use Wear' => [
            'group_type_code' => 'TM',
            'dependency' => null,
            'multiple' => true,
        ],
        'Passive Subtype' => [
            'group_type_code' => 'TM',
            'dependency' => ['Basic Typology.Passive'],
            'multiple' => true,
        ],
        'Active Subtype' => [
            'group_type_code' => 'TM',
            'dependency' => ['Basic Typology.Active (handheld)'],
            'multiple' => true,
        ],
        'Vessel Type' => [
            'group_type_code' => 'TM',
            'dependency' => ['Basic Typology.Vessel'],
            'multiple' => true,
        ],
        'Vessel Part' => [
            'group_type_code' => 'TM',
            'dependency' => ['Basic Typology.Vessel'],
            'multiple' => true,
        ],
        'Vessel Base' => [
            'group_type_code' => 'TM',
            'dependency' => ['Vessel Part.Base'],
            'multiple' => true,
        ],
        'Vessel Wall' => [
            'group_type_code' => 'TM',
            'dependency' => ['Vessel Part.Wall'],
            'multiple' => true,
        ],
        'Vessel Rim' => [
            'group_type_code' => 'TM',
            'dependency' => ['Vessel Part.Rim'],
            'multiple' => true,
        ],
        'Non-Processor Subtype' => [
            'group_type_code' => 'TM',
            'dependency' => ['Basic Typology.Non-Processor'],
            'multiple' => true,
        ],
        'Search-ID' => [
            'group_type_code' => 'CS',
            'column_name' => 'id',
        ],
        'Order By' => [
            'group_type_code' => 'OB',
            'params' => [
                ['name' => 'Excavation Date', 'column_name' => 'excavation_date'],
                ['name' => 'Catalog Date', 'column_name' => 'catalog_date'],
            ],
        ],
    ];

    public function __construct()
    {
        ModelGroup::__construct('Stone');
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
            ],
            'Registration' => [
                'Media',
                'Cataloger',
                'Whole'
            ],
            'Periods' => [
                'Periods (Top-Level)',
                'Neolithic Subperiods',
                'Bronze Subperiods',
                'Iron Subperiods',
                'Hellenistic Subperiods',
                'Roman Subperiods',
                'Early-Islamic Subperiods',
                'Medieval Subperiods',
                'Modern Subperiods',
            ],
            'Basic Characteristics' => [
                'Material',
                'Life Stage',
                'Use Wear',
                'Morphology',
                'Profile',
                'Production',
                'Basic Typology',
            ],
            'Typology' => [
                'Passive Subtype',
                'Active Subtype',
                'Vessel Type',
                'Vessel Part',
                'Vessel Base',
                'Vessel Wall',
                'Vessel Rim',
                'Non-Processor Subtype',
            ],
            'Order By' => [
                'Order By',
            ],
        ];

        return $this->buildTrio($cats);
    }
}
