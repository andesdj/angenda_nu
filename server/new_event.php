<?php
require('Conecta.php');

session_start();

if (isset($_SESSION['usuario'])) {
    $con = new Conecta();

    if ($con -> conexion_abrir() == 'OK') {

        $resultado = $con -> consultar_todo(['usuario'], ['idusuario'], "WHERE usuario ='" . $_SESSION['usuario'] . "'");
        $fila = $resultado -> fetch_assoc();
        $idusuario = $fila['idusuario'];

        $titulo = filter_input(INPUT_POST, 'titulo');
        $fecha_inicio = filter_input(INPUT_POST, 'start_date');
        $hora_inicio = filter_input(INPUT_POST, 'start_hour');
        $fecha_fin = filter_input(INPUT_POST, 'end_date');
        $hora_fin = filter_input(INPUT_POST, 'end_hour');

        $dia_completo = filter_input(INPUT_POST, 'allDay');
        if ($dia_completo == false) {
            $dia_completo = 1;
        } else {
            $dia_completo = 0;
        }

        $actividad['titulo'] = "'" . $titulo . "'";
        $actividad['fecha_inicio'] = "'" . $fecha_inicio . "'";
        $actividad['hora_inicio'] = "'" . $hora_inicio . "'";
        $actividad['fecha_fin'] = "'" . $fecha_fin . "'";
        $actividad['hora_fin'] = "'" . $hora_fin . "'";
        $actividad['dia_completo'] = "'" . $dia_completo . "'";
        $actividad['idusuario'] = $idusuario;

        if ($con -> agregar_registro('actividad', $actividad)) {
            $response['msg'] = "OK";
        } else {
            $response['msg'] = "Error al agregar actividad";
        }

        echo json_encode($response);

        $con -> conexion_cerrar();

    } else {
        echo "Se presentó un error en la conexión";
    }

} else {
    $response['msg'] = "No se ha iniciado una sesión";
}
