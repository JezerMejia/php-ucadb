<?php
include_once "conexion.php";

function insert_carrera(
  mysqli $mysql,
  string $nombre,
  string $descripcion,
  string $id_facultad,
) {
  $query =
    "INSERT INTO carrera (nombre, descripcion, id_facultad) VALUES(?, ?, ?);";

  $result = $mysql->execute_query($query, [
    $nombre,
    $descripcion,
    $id_facultad,
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

  $nombre = $_POST["nombre"];
  $descripcion = $_POST["descripcion"];
  $id_facultad = $_POST["id_facultad"];

  insert_carrera($mysql, $nombre, $descripcion, $id_facultad);

  $conn->disconnect();
} else {
  echo "Error desconocido";
}
