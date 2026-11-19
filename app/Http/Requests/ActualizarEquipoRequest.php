<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActualizarEquipoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation()
    {
        $this->merge([
            'codigo_patrimonial' => $this->codigo_patrimonial ? strtoupper(trim($this->codigo_patrimonial)) : null,
            'marca'              => $this->marca ? strtoupper(trim($this->marca)) : null,
            'modelo'             => $this->modelo ? strtoupper(trim($this->modelo)) : null,
            'cpu'                => $this->cpu ? strtoupper(trim($this->cpu)) : null,
            'ram'                => $this->ram ? strtoupper(trim($this->ram)) : null,
            'almacenamiento'     => $this->almacenamiento ? strtoupper(trim($this->almacenamiento)) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'codigo_patrimonial' => ['required', 'string', 'max:50', Rule::unique('equipos')->ignore($this->route('equipo'))],
            'marca'              => ['required', 'string', 'max:100'],
            'modelo'             => ['required', 'string', 'max:100'],
            'cpu'                => ['nullable', 'string', 'max:100'],
            'ram'                => ['nullable', 'string', 'max:50'],
            'almacenamiento'     => ['nullable', 'string', 'max:50'],
            'categoria_id'       => ['required', 'exists:categorias,id'],
            'estado'             => ['required', 'in:disponible,asignado,en_mantenimiento'],
            'oficina_id'         => ['nullable', 'required_if:estado,asignado', 'exists:oficinas,id'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $categoriaId = $this->input('categoria_id');
            $codigo = $this->input('codigo_patrimonial');
            $categoria = \App\Models\Categoria::find($categoriaId);

            if ($categoria) {
                $prefijos = ['Laptops' => 'LAP', 'Monitores' => 'MON', 'Impresoras' => 'IMP', 'Desktops' => 'DES'];
                $prefijoEsperado = $prefijos[$categoria->nombre] ?? 'EQ';

                if (!str_starts_with($codigo, $prefijoEsperado . '-')) {
                    $validator->errors()->add('codigo_patrimonial', "El código no es válido. Para la categoría {$categoria->nombre} debe empezar con '{$prefijoEsperado}-'.");
                }
            }
        });
    }
}