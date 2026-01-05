<?php

require_once __DIR__ . '/../modelos/usuarios_modelo.php';
require_once __DIR__ . '/../helpers/auth_helpers.php';

require_login();

$userSesion = auth_user();
$user = users_find_by_id($userSesion['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $intereses = array_map('trim', explode(',', $_POST['intereses']));

    users_update($user['id'], [
        'nombre' => $nombre,
        'intereses' => $intereses
    ]);

    header('Location: index.php?vista=perfil');
    exit;
}
