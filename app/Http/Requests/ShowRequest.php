<?php

namespace App\Http\Requests;

class ShowRequest extends DigRequest
{
    public function rules(): array
    {
        return [
            'model' => self::$rule_known_model_name,
            'id' => $this->rule_id_exists_in_model_table(),
        ];
    }
}
