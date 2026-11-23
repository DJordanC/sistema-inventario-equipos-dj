<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIE - DJ') — Sistema de Inventarios</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            /* Fondo más moderno y con más contraste */
            background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            border: none;
            border-radius: 1.2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        /* Estilos de los inputs */
        .input-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .form-control-custom {
            padding: 0.8rem 1rem 0.8rem 3rem; /* Espacio para el ícono izquierdo */
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 1rem;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        /* Efecto al hacer clic en el input */
        .form-control-custom:focus {
            background-color: #fff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .input-wrapper:focus-within .input-icon {
            color: #3b82f6; /* El ícono se pinta de azul al seleccionar */
        }

        /* Ojito de la contraseña (Alineado a la derecha) */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            z-index: 10;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #3b82f6;
        }

        /* Botón */
        .btn-custom {
            padding: 0.8rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                
                <!-- Encabezado / Logo -->
                <div class="text-center mb-4">
                    <h1 class="fw-bolder text-dark mb-1">
                        <i class="bi bi-pc-display-horizontal text-primary"></i> SIE - DJ
                    </h1>
                    <p class="text-secondary fw-semibold" style="letter-spacing: 0.5px;">Sistema de Inventarios de Equipos</p>
                </div>

                <!-- Contenido (Formulario) -->
                @yield('content')
                
                <!-- Footer -->
                <div class="text-center mt-4">
                    <small class="text-secondary">&copy; {{ date('Y') }} Departamento de Soporte Técnico DJ.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts específicos de la vista -->
    @stack('scripts')
</body>
</html>