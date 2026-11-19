<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarSolicitudRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'oficina_id'   => ['required', 'exists:oficinas,id'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'cantidad'     => ['required', 'integer', 'min:1'],
            'justificacion'=> ['required', 'string', 'max:500'],
        ];
    }
}