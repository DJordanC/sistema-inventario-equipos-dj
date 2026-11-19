<?php
namespace App\Enums;

enum RolUsuario: string
{
    case Administrador = 'administrador';
    case Solicitante   = 'solicitante';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Administrador => 'Administrador (Soporte Técnico)',
            self::Solicitante   => 'Solicitante (Jefe de Oficina)',
        };
    }
}