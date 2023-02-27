<?php
include_once "conexion.php";

function insert_facultad(
  mysqli $mysql,
  string $nombre,
  string $descripcion,
  string $ubicacion,
) {
  $query =
    "INSERT INTO facultad (nombre_facultad, descripcion_facultad, ubicacion_facultad) VALUES(?, ?, ?);";

  $result = $mysql->execute_query($query, [$nombre, $descripcion, $ubicacion]);

  if ($result) {
    echo "Registro guardado";
  } else {
    echo "Error al guardar el registro: {$mysql->error}";
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $conn = Connection::get_instance();
  $mysql = $conn->connect();

  $nombre = $_POST["nombre"];
  $descripcion = $_POST["descripcion"];
  $ubicacion = $_POST["ubicacion"];

  insert_facultad($mysql, $nombre, $descripcion, $ubicacion);

  $conn->disconnect();
} else {
  echo "Error desconocido";
}
