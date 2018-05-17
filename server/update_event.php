<?php
require('Conecta.php');

session_start();

if (isset($_SESSION['usuario'])) {
    $con = new Conecta();

    if ($con->conexion_abrir() == 'OK') {

        $idusuario = filter_input(INPUT_POST, 'id');
        $titulo = filter_input(INPUT_POST, 'title');
        $titulo_anterior = filter_input(INPUT_POST, 'title_anterior');
        $start_date = filter_input(INPUT_POST, 'start_date');
        $start_hour = filter_input(INPUT_POST, 'start_hour');
        $end_date = filter_input(INPUT_POST, 'end_date');
        $end_hour = filter_input(INPUT_POST, 'end_hour');

        $actividad = $con ->consultar_todo(['actividad'], ['idactividad'], 'WHERE idusuario=' . $idusuario . ' and titulo="' . $titulo_anterior . '"');

        $idactividad = '';
        if($actividad->num_rows > 0) {
            $fila = $actividad->fetch_assoc();
            $idactividad = $fila['idactividad'];
        }

        $condition = "idactividad = " . $idactividad ."  AND idusuario = '" . $idusuario . "'";

        $data['titulo'] = "'" . $titulo . "'";
        $data['fecha_inicio'] = "'" . $start_date . "'";
        $data['hora_inicio'] = "'" . $start_hour . "'";
        $data['fecha_fin'] = "'" . $end_date . "'";
        $data['hora_fin'] = "'". $end_hour . "'";

        if ($con->actualizar_registro('actividad', $data, $condition)) {
            $response['msg'] = "OK";
        } else {
            $response['msg'] = "Error al actualizar la actividad";
        }

        echo json_encode($response);

        $con->conexion_cerrar();

    }else {
        echo "Se presentó un error en la conexión";
    }

}else {
    $response['msg'] = "No se ha iniciado una sesión";
}
