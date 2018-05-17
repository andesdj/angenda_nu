<?php
require('Conecta.php');

session_start();

if (isset($_SESSION['usuario'])) {
    $con = new Conecta();

    if ($con->conexion_abrir() =='OK') {

        $idusuario = filter_input(INPUT_POST, 'id');
        $titulo = filter_input(INPUT_POST, 'title');
        $condition = "titulo = '" . $titulo . "' AND idusuario = " . $idusuario;

        if ($con->eliminar_registro('actividad', $condition)) {
            $response['msg'] = "OK";
        } else {
            $response['msg'] = "Error al eliminar";
        }

        echo json_encode($response);

        $con->conexion_cerrar();

    }else {
        echo "Error en la conexión";
    }

} else {
    $response['msg'] = "No se ha iniciado una sesión";
}
