<?php

namespace App\Http\Requests;

class TagSyncRequest extends DigRequest
{
    private static $modelInfo = [
        'Locus' => ['tag_table_name' => 'loci_tags', 'fields' => []],
        'Pottery' => ['tag_table_name' => 'pottery_tags', 'fields' => ['area']],
        'Stone' => ['tag_table_name' => 'stone_tags', 'fields' => ['material_id', 'base_type_id', 'cataloger_id']],
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $p = $this->input('model').'-tag';

        return $this->user('sanctum')->can($p);
    }

    public function rules(): array
    {
        //TODO  column_values Rule
        $info = self::$modelInfo[$this->input('model')];
        $tag_table_name = $info['tag_table_name'];
        $tag_id_exists_rule = 'exists:'.$tag_table_name.',id';

        $column_names = collect($info['fields'])->implode(',');
        $column_name_rule = 'in:'.$column_names;

        return [
            'model' => self::$rule_known_model_name,
            'id' => $this->rule_id_exists_in_model_table(),
            'model_tag_ids.*' => $tag_id_exists_rule,
            'ids.*' => 'exists:tags,id',
            'columns.*.column_name' => $column_name_rule,
            'columns.*.val' => '',
            'columns.*.paramKey' => '',
        ];
    }
}
