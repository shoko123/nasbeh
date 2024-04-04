<?php

namespace App\Http\Requests;

class PageRequest extends DigRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        //TODO  column_values Rule

        return [
            'model' => self::$rule_known_model_name,
            'ids.*' => $this->rule_id_exists_in_model_table(),
            'view' => 'required|in:Tabular,Gallery',
        ];
    }
}
