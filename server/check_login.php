<?php
require('Conecta.php');

$usuario = filter_input(INPUT_POST, 'username');
$contrasenia = filter_input(INPUT_POST, 'password');

$conecto = new Conecta();
$response = [ ];

if ($conecto -> conexion_abrir() == 'OK') {
    $usuarios_lista = $conecto -> consultar_todo(['usuario'], ['usuario', 'contrasenia'], 'WHERE usuario="'. $usuario .'"');

    if ($usuarios_lista->num_rows != 0) {
        $fila = $usuarios_lista -> fetch_assoc();
        if (password_verify($contrasenia, $fila['contrasenia'])) {
            $response['mensaje'] = 'OK';
            session_start();
            $_SESSION['usuario'] = $fila['usuario'];
        } else {
            $response['mensaje'] = 'Contraseña incorrecta';
            $response['acceso'] = 'rechazado';
        }
    } else {
        $response['mensaje'] = 'Usuario incorrecto';
        $response['acceso'] = 'rechazado';
    }
}

echo json_encode($response);

$conecto -> conexion_cerrar();
