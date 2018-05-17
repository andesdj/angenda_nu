<?php

require('Conecta.php');

session_start();

if (isset($_SESSION['usuario'])) {
    $con = new Conecta();
    $response = [ ];

    if ($con -> conexion_abrir() == 'OK') {

        $resultado_consulta = $con -> consultar_todo(['usuario'], ['idusuario'], "WHERE usuario ='" . $_SESSION['usuario'] . "'");

        $fila = $resultado_consulta -> fetch_assoc();
        $idusuario = $fila['idusuario'];

        $resultado_consulta_actividad = $con -> consultar_todo(['actividad'], ['*'], "WHERE idusuario ='" . $idusuario . "'");

        if ($resultado_consulta_actividad -> num_rows != 0) {

            $i = 0;
            while ($fila = $resultado_consulta_actividad -> fetch_assoc()) {
                $response['eventos'][$i]['id'] = $fila['idusuario'];
                $response['eventos'][$i]['title']=$fila['titulo'];
                $response['eventos'][$i]['start']=$fila['fecha_inicio'].' '.$fila['hora_inicio'];
                $response['eventos'][$i]['end']=$fila['fecha_fin'].' '.$fila['hora_fin'];
                $response['eventos'][$i]['allDay']=boolval($fila['dia_completo']);
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


