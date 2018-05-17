<?php
/**
 * Created by PhpStorm.
 * User: PabloAntonio
 * Date: 14/10/2017
 * Time: 1:14 PM
 */

class Conecta {
    private $server = 'localhost';
    private $usuario = 'agenda';
    private $contrasenia = 'nextU';
    private $base_datos = 'agenda';
    private $conexion;

    function conexion_abrir() {
        $this -> conexion = new mysqli($this -> server, $this -> usuario, $this -> contrasenia, $this -> base_datos);
        if ($this -> conexion -> connect_error) {
            return "Error de conexión:" . $this -> conexion -> connect_error;
        } else {
            return "OK";
        }
    }

    public function getConexion() {
        return $this -> conexion;
    }

    public function conexion_cerrar() {
        $this -> conexion -> close();
    }

    public function ejecuta_consulta($consulta) {
        return $this -> conexion -> query($consulta);
    }

    public function agregar_registro($tabla, $data) {
        $sql = 'INSERT INTO ' . $tabla;
        $sql .= ' (';
        $i = 1;
        foreach ($data as $key => $value) {
            $sql .= $key;
            if ($i < count($data)) {
                $sql .= ', ';
            } else $sql .= ')';
            $i++;
        }
        $sql .= ' VALUES (';
        $i = 1;
        foreach ($data as $key => $value) {
            $sql .= $value;
            if ($i < count($data)) {
                $sql .= ', ';
            } else $sql .= ');';
            $i++;
        }
        return $this -> ejecuta_consulta($sql);
    }

    public function actualizar_registro($tabla, $data, $condicion) {
        $sql = 'UPDATE ' . $tabla . ' SET ';
        $i = 1;
        foreach ($data as $key => $value) {
            $sql .= $key . '=' . $value;
            if ($i < sizeof($data)) {
                $sql .= ', ';
            } else $sql .= ' WHERE ' . $condicion . ';';
            $i++;
        }
        return $this -> ejecuta_consulta($sql);
    }

    public function eliminar_registro($tabla, $condicion) {
        $sql = "DELETE FROM " . $tabla . " WHERE " . $condicion . ";";
        return $this -> ejecuta_consulta($sql);
    }

    public function consultar_todo($tablas, $campos, $condicion = "") {
        $sql = "SELECT ";
        $keys = array_keys($campos);
        $ultima_key = end($keys);
        foreach ($campos as $key => $value) {
            $sql .= $value;
            if ($key != $ultima_key) {
                $sql .= ", ";
            } else $sql .= " FROM ";
        }

        $keys = array_keys($tablas);
        $ultima_key = end($keys);
        foreach ($tablas as $key => $value) {
            $sql .= $value;
            if ($key != $ultima_key) {
                $sql .= ", ";
            } else $sql .= " ";
        }

        if ($condicion == "") {
            $sql .= ";";
        } else {
            $sql .= $condicion . ";";
        }
        return $this -> ejecuta_consulta($sql);
    }

    public function agregar_usuario($nombre_completo, $usuario, $contrasenia) {
        $sql = 'INSERT INTO usuario (nombre_completo, usuario, contrasenia) VALUES ("' . $nombre_completo . '", "' . $usuario . '", "' . $contrasenia . '")';
        return $this -> ejecuta_consulta($sql);
    }
}