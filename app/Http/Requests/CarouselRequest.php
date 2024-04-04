<?php

namespace App\Http\Requests;

class CarouselRequest extends DigRequest
{
    public function rules(): array
    {
        $table_name = self::$modelToTableName[$this->input('model')];

        return [
            'model' => self::$rule_known_model_name,
            'id' => 'required|exists:'.$table_name.',id',
        ];
    }
}
