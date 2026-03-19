<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DaloWeb</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon/favicon.svg') }}">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0f0f1a;
            color: #e8e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .login__logo {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.75rem;
            font-weight: 700;
            color: #e8e8f0;
            text-decoration: none;
            display: block;
        }

        .login__logo span {
            color: #6c5ce7;
        }

        .login__card {
            background: #1a1a2e;
            border: 1px solid #2a2a40;
            border-radius: 12px;
            padding: 2rem;
        }

        .login__title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .login__group {
            margin-bottom: 1.25rem;
        }

        .login__label {
            display: block;
            font-size: .875rem;
            color: #a0a0b8;
            margin-bottom: .4rem;
        }

        .login__input {
            width: 100%;
            padding: .75rem 1rem;
            background: #0f0f1a;
            border: 1px solid #2a2a40;
            border-radius: 8px;
            color: #e8e8f0;
            font-size: 1rem;
            transition: border-color .3s;
        }

        .login__input:focus {
            outline: none;
            border-color: #6c5ce7;
        }

        .login__remember {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.5rem;
            font-size: .875rem;
            color: #a0a0b8;
        }

        .login__remember input[type="checkbox"] {
            accent-color: #6c5ce7;
        }

        .login__btn {
            width: 100%;
            padding: .85rem;
            background: #6c5ce7;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .3s;
        }

        .login__btn:hover {
            background: #5a4bd1;
        }

        .login__error {
            background: rgba(239, 68, 68, .15);
            border: 1px solid rgba(239, 68, 68, .3);
            color: #fca5a5;
            padding: .75rem 1rem;
            border-radius: 8px;
            font-size: .875rem;
            margin-bottom: 1.25rem;
        }

        .login__back {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #a0a0b8;
            font-size: .875rem;
            text-decoration: none;
            transition: color .3s;
        }

        .login__back:hover {
            color: #6c5ce7;
        }
    </style>
</head>

<body>
    <div class="login">
        <a href="{{ route('inicio') }}" class="login__logo">Dalo<span>Web</span></a>

        <div class="login__card">
            <h1 class="login__title">Acceso administración</h1>

            @if ($errors->any())
            <div class="login__error">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="login__group">
                    <label for="correo" class="login__label">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" class="login__input"
                        value="{{ old('correo') }}" required autofocus>
                </div>

                <div class="login__group">
                    <label for="contrasena" class="login__label">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" class="login__input" required>
                </div>

                <label class="login__remember">
                    <input type="checkbox" name="recordarme"> Recordarme
                </label>

                <button type="submit" class="login__btn">Iniciar sesión</button>
            </form>
        </div>

        <a href="{{ route('inicio') }}" class="login__back">&larr; Volver a DaloWeb</a>
    </div>
</body>

</html>