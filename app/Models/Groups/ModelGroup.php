<?php

namespace App\Models\Groups;

use App\Models\Tags\TagGroup;
use Exception;
use Illuminate\Support\Facades\DB;

abstract class ModelGroup
{
    abstract public static function getModelGroups(): array;

    abstract public function trio(): array;

    protected $eloquent_model_name;

    protected static $groups;

    public function __construct($eloquent_model_name = null)
    {
        $this->eloquent_model_name = $eloquent_model_name;
    }

    public function getModelGroup(string $group_name): array
    {
        return self::$groups[$group_name];
    }

    public function getGroupDetails($group_name): array
    {
        $group = $this->getModelGroup($group_name) ?? null;
        if (is_null($group)) {
            throw new Exception('***MODEL INIT() ERROR*** Group * '.$group_name.' * NOT FOUND');
        }

        switch ($group['group_type_code']) {
            case 'TG': //tags global
                return $this->getGlobalTagsGroupDetails($group_name, $group);

            case 'TM': //tags model
                return $this->getModelTagsGroupDetails($group_name, $group);

            case 'CV': //column values
            case 'CR': //column value to be used only for filtering
                return $this->getColumnGroupDetails($group_name, $group);

            case 'CB': //column boolean
                return $this->getColumnBooleanDetails($group_name, $group);

            case 'CL': //column lookup values
                return $this->getLookupGroupDetails($group_name, $group);

            case 'CS': //column search
                return $this->getTextualSearchGroupDetails($group_name, $group);

            case 'BF': //bespoke
                return $this->getBespokeFilterGroupDetails($group_name, $group);

            case 'OB': //order by values
                return $this->getOrderByDetails($group_name, $group);
            case 'MD': //media
                return array_merge($group, [
                    'group_name' => $group_name,
                    'params' => null,
                ]);
        }

        return [];
    }

    private function getLookupGroupDetails($group_name, $group)
    {
        $params = DB::table($group['table_name'])->get();

        return array_merge($group, [
            'group_name' => $group_name,
            'params' => $params,
        ]);
    }

    private function getModelTagsGroupDetails($group_name, $group)
    {
        $tagGroupName = 'App\\Models\\Tags\\'.$this->eloquent_model_name.'TagGroup';
        $tg = new $tagGroupName;

        $tagGroup = $tg->with(['tags' => function ($q) {
            $q->select('id', 'name', 'group_id');
        }])
            ->select('id', 'multiple')
            ->where('name', $group_name)
            ->first();

        if (is_null($tagGroup)) {
            throw new Exception('***MODEL INIT() ERROR*** Group * '.$group_name.' * NOT FOUND');
            //dd("***MODEL INIT() ERROR*** Group \"" . $data->name . "\" NOT FOUND");
        }

        return array_merge($group, [
            'group_name' => $group_name,
            'group_id' => $tagGroup->id,
            'multiple' => $tagGroup->multiple,
            'params' => $tagGroup->tags->map(function ($y) {
                return ['id' => $y->id, 'name' => $y->name];
            }),
        ]);
    }

    private function getGlobalTagsGroupDetails($group_name, $group)
    {
        $gtg = TagGroup::with(['tags' => function ($q) {
            $q->select('id', 'name', 'group_id');
        }])
            ->select('id', 'name')
            ->where('name', $group_name)
            ->first();

        return array_merge($group, [
            'group_name' => $group_name,
            'group_id' => $gtg->id,
            'multiple' => true,
            'params' => $gtg->tags->map(function ($y) {
                return ['id' => $y->id, 'name' => $y->name];
            }),
        ]);
    }

    private function getColumnGroupDetails($group_name, $group)
    {
        $column_name = $group['column_name'];
        $params = DB::table($group['table_name'])->select($column_name)->distinct()->orderBy($column_name)->get();

        return array_merge($group, [
            'group_name' => $group_name,
            //"params"  => $params
            'params' => $params->map(function ($y, $key) use ($column_name) {
                return $y->$column_name;
            }),
        ]);
    }

    private function getColumnBooleanDetails($group_name, $group)
    {
        return array_merge($group, [
            'group_name' => $group_name,
        ]);
    }

    private function getTextualSearchGroupDetails($group_name, $group)
    {
        $group = $this->getModelGroup($group_name) ?? null;
        if (is_null($group)) {
            throw new Exception('***MODEL INIT() ERROR*** Group * '.$group_name.' * NOT FOUND');
        }

        return [
            'group_type_code' => 'CS',
            'group_name' => $group_name,
            'column_name' => $group['column_name'],
            'params' => null,
        ];
    }

    private function getBespokeFilterGroupDetails($group_name, $group)
    {
        $paramsFormatted = collect($group['params'])->map(function ($y, $key) {
            return ['id' => $key, 'name' => $y];
        });
        $group['params'] = $paramsFormatted;
        $group['group_name'] = $group_name;

        return $group;
    }

    private function getOrderByDetails($group_name, $group)
    {
        return array_merge($group, [
            'group_name' => $group_name,
        ]);
    }

    public function buildTrio($cats): array
    {
        $trio = [];

        foreach ($cats as $name => $group_names) {
            $category = ['name' => $name, 'groups' => []];
            foreach ($group_names as $group_name) {
                array_push($category['groups'], $this->getGroupDetails($group_name));
            }
            array_push($trio, $category);
        }

        return ['trio' => $trio];
    }
}
