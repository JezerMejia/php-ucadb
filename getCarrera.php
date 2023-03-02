<?php
include_once "conexion.php";

/**
 * Realiza una búsqueda de las carreras por código de registro
 */
function get_carrera_by_code(mysqli $mysql, string $code): string {
  $result = $mysql->execute_query("SELECT * FROM carrera WHERE codigo = ?", [
    $code,
  ]);

  $carrera = $result->fetch_assoc();
  $carrera = json_encode($carrera);
  $carrera = trim($carrera);

  $result->close();
  return $carrera;
}

/**
 * Realiza una búsqueda de las carreras por nombre de facultad
 */
function get_carrera_by_facultad(mysqli $mysql, string $nombre): string {
  $result = $mysql->execute_query(
    "SELECT * FROM facultad WHERE nombre = ?",
    [$nombre],
  );

  $facultad = $result->fetch_assoc();
  if (!$facultad) {
    return "";
  }
  $id = $facultad["codigo"];

  $result = $mysql->execute_query(
    "SELECT * FROM carrera WHERE id_facultad = ?",
    [$id],
  );

  $carreras = "";
  if ($mysql->affected_rows > 0) {
    $carreras = "{\"data\": [";
    while ($row = $result->fetch_assoc()) {
      $carreras = $carreras . json_encode($row);
      $carreras = $carreras . ",";
    }

    $carreras = trim($carreras);
    $carreras = $carreras . "]}";
  }

  $result->close();
  return $carreras;
}

/**
 * Obtiene todas las carreras
 */
function get_all_carreras(mysqli $mysql): string {
  $result = $mysql->query("SELECT * FROM carrera;");

  $carreras = "";
  if ($mysql->affected_rows > 0) {
    $carreras = "{\"data\": [";
    while ($row = $result->fetch_assoc()) {
      $carreras = $carreras . json_encode($row);
      $carreras = $carreras . ",";
    }

    $carreras = trim($carreras);
    $carreras = $carreras . "]}";
  }

  $result->close();
  return $carreras;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $conn = Connection::get_instance();
  $mysql = $conn->connect();

  if (isset($_GET["codigo"])) {
    $id_carrera = $_GET["codigo"];
    $carrera = get_carrera_by_code($mysql, $id_carrera);
    echo $carrera;
    return;
  } elseif (isset($_GET["facultad"])) {
    $nombre_facultad = $_GET["facultad"];
    $carrera = get_carrera_by_facultad($mysql, $nombre_facultad);
    echo $carrera;
    return;
  }

  $carreras = get_all_carreras($mysql);
  echo $carreras;

  $conn->disconnect();
}
