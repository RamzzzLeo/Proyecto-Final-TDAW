<?php

require_once __DIR__ . '/../modelos/usuarios_modelo.php';
require_once __DIR__ . '/sesion_controlador.php';
require_once __DIR__ . '/../../config.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // REGISTRO
    if (isset($_POST['register'])) {
        $nombre = trim($_POST['nombre']);
        $correo = trim($_POST['correo']);
        $password = $_POST['password'];

        if ($nombre === '' || $correo === '' || $password === '') {
            $errores[] = 'Todos los campos son obligatorios';
        }

        if (RESTRINGIR_CORREO_INSTITUCIONAL && !str_ends_with($correo, '@escom.ipn.mx')) {
            $errores[] = 'Correo institucional requerido';
        }

        if (users_find_by_email($correo)) {
            $errores[] = 'El correo ya está registrado';
        }

        if (empty($errores)) {
            $user = users_create(compact('nombre', 'correo', 'password'));
            auth_login($user);
            header('Location: index.php?vista=perfil');

            exit;
        }
    }

    // LOGIN
    if (isset($_POST['login'])) {
        $correo = trim($_POST['correo']);
        $password = $_POST['password'];

        $user = users_find_by_email($correo);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errores[] = 'Credenciales incorrectas';
        } else {
            auth_login($user);
            header('Location: index.php?vista=perfil');

            exit;
        }
    }
}
