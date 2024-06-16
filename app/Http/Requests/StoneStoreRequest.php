<?php

namespace App\Http\Requests;

class StoneStoreRequest extends DigModelStoreRequest
{
    //authorization done at DigModelStoreRequest.php

    private $rules = [];

    public static $base_rules = [
        'item.id' => 'required|max:50',
        'item.id_year' => 'required|numeric|between:9,30',
        'item.id_access_no' => 'required|numeric|between:1,5',
        'item.id_object_no' => 'required|numeric|between:1,9999',
        'item.specialist_description' => 'max:250',
        'item.specialist_date' => 'date|nullable'
    ];

    public static $non_oc_rules = [
        'item.square' => 'max:250',
        'item.context' => 'max:250',
        'item.excavation_date' => 'date|nullable',
        'item.occupation_level' => 'max:250',
        'item.cataloger_material' => 'max:250',
        'item.whole' => 'boolean',
        'item.cataloger_typology' => 'max:250',
        'item.cataloger_description' => 'max:250',
        'item.conservation_notes' => 'max:250',
        'item.weight' => 'max:250',
        'item.length' => 'max:250',
        'item.width' => 'max:250',
        'item.height' => 'max:250',
        'item.diameter' => 'max:250',
        'item.dimension_notes' => 'max:250',
        'item.cultural_period' => 'max:250',
        'item.excavation_object_id' => 'max:250',
        'item.old_museum_id' => 'max:250',
        'item.cataloger_id' =>  'exists:stone_catalogers,id',
        'item.catalog_date' => 'date|nullable',
        'item.specialist_description' => 'max:250',
        'item.specialist_date' => 'date|nullable',
        'item.thumbnail' => 'max:250',
        'item.material_id' => 'exists:stone_materials,id',
        'item.base_type_id' => 'exists:stone_base_types,id',
    ];

    public function rules(): array
    {
        return $this->rules;
    }

    protected function prepareForValidation(): void
    {
        if (is_null($this->input('uri'))) {
            $this->rules = array_merge(self::$base_rules, self::$non_oc_rules);
        } else {
            $this->rules = self::$base_rules;
        }
    }
}
