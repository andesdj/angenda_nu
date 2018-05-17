<?php
require('Conecta.php');

$con = new Conecta();

if ($con -> conexion_abrir() == 'OK') {

    $data_usuario[0]['nombre_completo'] = "'Pablo Hurtado'";
    $data_usuario[0]['usuario'] = "'phurtado1112@gmail.com'";
    $data_usuario[0]['contrasenia'] = "'" . password_hash("alepa1", PASSWORD_DEFAULT) . "'";

    $data_usuario[1]['nombre_completo'] = "'Antonio Díaz'";
    $data_usuario[1]['usuario'] = "'phurtado@phd-systems.com'";
    $data_usuario[1]['contrasenia'] = "'" . password_hash("Alepa1", PASSWORD_DEFAULT) . "'";

    $data_usuario[2]['nombre_completo'] = "'Pablo Díaz'";
    $data_usuario[2]['usuario'] = "'pablohur@hotmail.com'";
    $data_usuario[2]['contrasenia'] = "'" . password_hash("alepa1", PASSWORD_DEFAULT) . "'";

    foreach ($data_usuario as $key => $values) {
        if ($con -> agregar_registro('usuario', $values)) {
            echo "Los datos de usuarios se agregaron correctamente";
        } else {
            echo "Error agregar usuarios";
        }
    }

    $con -> conexion_cerrar();

} else {
    echo "Se presentó un error en la conexión";
}

