<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DigModelStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $p = $this->input('model').'-'.(($this->method() == 'POST') ? 'create' : 'update');

        return $this->user('sanctum')->can($p);
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
}
