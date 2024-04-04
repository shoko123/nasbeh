<?php

namespace App\Http\Requests;

class MediaReorderRequest extends DigRequest
{
    public function authorize(): bool
    {
        $p = $this->input('model').'-media';

        return $this->user('sanctum')->can($p);
    }

    public function rules(): array
    {
        return [
            'model' => self::$rule_known_model_name,
            'model_id' => $this->rule_id_exists_in_model_table(),
            'ordered.*.id' => 'nullable|exists:media,id',
            'ordered.*.order' => 'numeric|integer',
        ];
    }
}
