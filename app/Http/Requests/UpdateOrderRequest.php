<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;  // Importar la interfaz Validator 
use Illuminate\Http\Exceptions\HttpResponseException;  // Importar la excepción HttpResponseException

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
              'user_id' => 'sometimes|exists:users,id',
            'branch_id' => 'sometimes|exists:branches,id',
            'fecha_pedido' => 'sometimes|date',
            'fecha_recogida' => 'sometimes|date|after_or_equal:today',
            'estado' => 'sometimes|in:pendiente,preparando,listo,entregado',
            'total' => 'sometimes|numeric|min:0',
        ];
    }
    // Manejar la falla de validación y devolver una respuesta JSON personalizada
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422));
    }
}
