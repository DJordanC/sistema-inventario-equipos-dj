<?php
namespace App\Actions\Fortify;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): Usuario
    {
        Validator::make($input, [
            'nombre'   => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'password' => $this->passwordRules(),
        ])->validate();

        return Usuario::create([
            'nombre'   => $input['nombre'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
            'rol'      => 'solicitante', // Por defecto, los que se registran son solicitantes
        ]);
    }
}