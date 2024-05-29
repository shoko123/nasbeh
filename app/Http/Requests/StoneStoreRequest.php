<?php

namespace App\Http\Requests;

class StoneStoreRequest extends DigModelStoreRequest
{
    //authorization done at DigModelStoreRequest.php

    private $rules = [];

    public static $base_rules = [
        'item.id' => 'required|max:50',
        'item.specialist_description' => 'max:250',
        'item.specialist_date' => 'date|nullable'
    ];

    public static $create_rules = [
        'item.material_id' => 'exists:stone_materials,id',
        'item.base_type_id' => 'exists:stone_base_types,id',
    ];

    public static $update_rules = [
        'item.id' => 'exists:stones,id',
    ];

    public function rules(): array
    {
        return $this->rules;
    }

    protected function prepareForValidation(): void
    {
        if ($this->method() === 'POST') {
            $this->rules = array_merge(self::$base_rules, self::$create_rules);
        } else {
            $this->rules = array_merge(self::$base_rules, self::$update_rules);
        }
    }
}
