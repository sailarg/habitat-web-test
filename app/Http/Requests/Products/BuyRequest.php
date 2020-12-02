<?php

namespace App\Http\Requests\Products;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BuyRequest extends FormRequest
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
            'quantity' => 'required|numeric'
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
