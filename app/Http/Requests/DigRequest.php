<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DigRequest extends FormRequest
{
    public static $modelToTableName = [
        'Locus' => 'loci',
        'Pottery' => 'pottery',
        'Stone' => 'stones',
    ];

    public static $rule_known_model_name = 'required|in:Locus,Pottery,Stone';

    protected function tableName(): string
    {
        return self::$modelToTableName[$this->input('model')];
    }

    protected function rule_id_exists_in_model_table(): string
    {
        return 'required|exists:'.$this->tableName().',id';
    }

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
        return [];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], 400));
    }
}
