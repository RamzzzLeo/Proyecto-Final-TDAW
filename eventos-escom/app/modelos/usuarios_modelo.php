<?php
declare(strict_types=1);

function users_path(): string {
    return __DIR__ . '/../../storage/datos/users.json';
}

function users_all(): array {
    $path = users_path();
    if (!file_exists($path)) {
        @mkdir(dirname($path), 0777, true);
        file_put_contents($path, "[]");
    }
    $json = file_get_contents($path);
    $data = json_decode($json ?: "[]", true);
    return is_array($data) ? $data : [];
}

function users_save_all(array $users): void {
    $path = users_path();
    @mkdir(dirname($path), 0777, true);

    $fp = fopen($path, 'c+');
    if (!$fp) throw new RuntimeException("No se pudo abrir users.json");

    flock($fp, LOCK_EX);
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
}

function users_find_by_email(string $correo): ?array {
    $correo = strtolower(trim($correo));
    foreach (users_all() as $u) {
        if (strtolower($u['correo'] ?? '') === $correo) return $u;
    }
    return null;
}

function users_next_id(array $users): int {
    $max = 0;
    foreach ($users as $u) $max = max($max, (int)($u['id'] ?? 0));
    return $max + 1;
}

function users_create(string $nombre, string $correo, string $password, string $rol = 'estudiante'): array {
    $users = users_all();

    $nuevo = [
        'id' => users_next_id($users),
        'nombre' => $nombre,
        'correo' => strtolower(trim($correo)),
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'rol' => $rol,
        'intereses' => [],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    $users[] = $nuevo;
    users_save_all($users);
    return $nuevo;
}
