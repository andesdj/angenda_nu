<?php
require('Conecta.php');

session_start();

if (isset($_SESSION['usuario'])) {
    $con = new Conecta();
    $response = [ ];

    if ($con -> conexion_abrir() == 'OK') {

        $idusuario = filter_input(INPUT_POST, 'id');
        $titulo = filter_input(INPUT_POST, 'title');
        $fecha_inicio = filter_input(INPUT_POST, 'start_date');

        $resultado_consulta_actividad = $con -> consultar_todo(['actividad'], ['*'], "WHERE idusuario ='" . $idusuario . "' and titulo='" . $titulo . "' and fecha_inicio='" . $fecha_inicio . "'");

        if ($resultado_consulta_actividad -> num_rows != 0) {

            $i = 0;
            while ($fila = $resultado_consulta_actividad -> fetch_assoc()) {
                $response['id'] = $fila['idusuario'];
                $response['title']=$fila['titulo'];
                $response['start']=$fila['fecha_inicio'].' '.$fila['hora_inicio'];
                $response['end']=$fila['fecha_fin'].' '.$fila['hora_fin'];
                $response['allDay']=boolval($fila['dia_completo']);
                $i++;
            }
            $response['msg'] = "OK";
        }

        echo json_encode($response);

        $con -> conexion_cerrar();
    } else {
        echo "Error de conexión";
    }
} else {
    $response['msg'] = "No se ha iniciado sesión";
}