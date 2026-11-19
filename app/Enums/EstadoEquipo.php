<?php
namespace App\Enums;

enum EstadoEquipo: string
{
    case Disponible      = 'disponible';
    case Asignado        = 'asignado';
    case EnMantenimiento = 'en_mantenimiento';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Disponible      => 'Disponible',
            self::Asignado        => 'Asignado',
            self::EnMantenimiento => 'En Mantenimiento',
        };
    }
}