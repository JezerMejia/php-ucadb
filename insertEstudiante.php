<?php
include_once "conexion.php";

function insert_estudiante(
  mysqli $mysql,
  string $nombres,
  string $apellidos,
  string $fecha_nacimiento,
  string $id_carrera,
) {
  $query =
    "INSERT INTO estudiante (nombres, apellidos, fecha_nacimiento, id_carrera) VALUES(?, ?, ?, ?);";

  $result = $mysql->execute_query($query, [
    $nombres,
    $apellidos,
    $fecha_nacimiento,
    $id_carrera,
  ]);

  if ($result) {
    echo "Registro guardado";
  } else {
    echo "Error al guardar el registro: {$mysql->error}";
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $conn = Connection::get_instance();
  $mysql = $conn->connect();

  $nombres = $_POST["nombres"];
  $apellidos = $_POST["apellidos"];
  $fecha_nacimiento = $_POST["fecha_nacimiento"];
  $id_carrera = $_POST["id_carrera"];

  insert_estudiante($mysql, $nombres, $apellidos, $fecha_nacimiento, $id_carrera);

  $conn->disconnect();
} else {
  echo "Error desconocido";
}
