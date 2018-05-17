<?php
require('Conecta.php');

$con = new Conecta();

if ($con -> conexion_abrir() == 'OK') {

    $nombre_completo = filter_input(INPUT_POST, 'nombre_completo');
    $usuario = filter_input(INPUT_POST, 'usuario');
    $contrasenia = filter_input(INPUT_POST, 'contrasenia');

    $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);

    if ($con -> agregar_usuario($nombre_completo, $usuario, $contrasenia_hash)) {
        $response['mensaje'] = "OK";
    } else {
        $response['mensaje'] = "Error al agregar usuario a la db";
    }

    echo json_encode($response);

    $con ->conexion_cerrar();

} else {

    echo "Error de conexión a la base de datos";

}


