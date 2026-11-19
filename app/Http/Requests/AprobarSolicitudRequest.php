<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AprobarSolicitudRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'equipo_ids'   => ['required', 'array'],
            'equipo_ids.*' => ['exists:equipos,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'equipo_ids.required' => 'Debe seleccionar los equipos a asignar.',
        ];
    }
}