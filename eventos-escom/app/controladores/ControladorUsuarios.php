<?php
declare(strict_types=1);

require_once __DIR__ . '/../modelos/usuarios_modelo.php';
require_once __DIR__ . '/sesion_controlador.php';

final class ControladorUsuarios
{
    public function login(callable $render): void {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = strtolower(trim($_POST['correo'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
            if ($password === '') $errores[] = "Contraseña requerida.";

            if (!$errores) {
                $u = users_find_by_email($correo);
                if (!$u || !password_verify($password, $u['password_hash'] ?? '')) {
                    $errores[] = "Credenciales incorrectas.";
                } else {
                    auth_login($u);
                    header('Location: index.php?vista=perfil');
                    exit;
                }
            }
        }

        $render('auth/login.php', [
            'tituloPagina' => 'Login',
            'errores' => $errores
        ]);
    }

    public function register(callable $render): void {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = strtolower(trim($_POST['correo'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            if ($nombre === '') $errores[] = "Nombre requerido.";
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
            if (strlen($password) < 6) $errores[] = "La contraseña debe tener al menos 6 caracteres.";

            if (!$errores && users_find_by_email($correo)) {
                $errores[] = "Ese correo ya está registrado.";
            }

            if (!$errores) {
                $nuevo = users_create($nombre, $correo, $password, 'estudiante');
                auth_login($nuevo);
                header('Location: index.php?vista=perfil');
                exit;
            }
        }

        $render('auth/register.php', [
            'tituloPagina' => 'Registro',
            'errores' => $errores
        ]);
    }

    public function perfil(callable $render): void {
        requiere_sesion();
        $render('auth/perfil.php', [
            'tituloPagina' => 'Perfil',
            'user' => auth_user(),
        ]);
    }

    public function logout(): void {
        auth_logout();
        header('Location: index.php?vista=login');
        exit;
    }
}
