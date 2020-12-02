<?php

namespace App\Http\Requests\Buyers;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetDetailRequest extends FormRequest
{
    use ApiResponse;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:users,id'
        ];
    }

    /**
     * Custom format errors.
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->showError(
            'No se pudo procesar la petición',
            $validator->errors()->all(),
            400
        ));
    }
}
