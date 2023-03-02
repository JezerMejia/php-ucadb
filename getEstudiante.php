<?php
include_once "conexion.php";

/**
 * Realiza una búsqueda de las estudiantes por código de registro
 */
function get_estudiante_by_code(mysqli $mysql, string $code): string {
  $result = $mysql->execute_query("SELECT * FROM estudiante WHERE codigo = ?", [
    $code,
  ]);

  $estudiante = $result->fetch_assoc();
  $estudiante = json_encode($estudiante);
  $estudiante = trim($estudiante);

  $result->close();
  return $estudiante;
}

/**
 * Realiza una búsqueda de los estudiantes por código de carrera
 */
function get_estudiante_by_carrera(mysqli $mysql, string $code): string {
  $result = $mysql->execute_query(
    "SELECT * FROM carrera WHERE codigo = ?",
    [$code],
  );

  $carrera = $result->fetch_assoc();
  if (!$carrera) {
    return "";
  }
  $id = $carrera["codigo"];
  $nombre_carrera = $carrera["nombre"];

  $result = $mysql->execute_query(
    "SELECT * FROM estudiante WHERE id_carrera = ?",
    [$id],
  );

  $estudiantes = "{\"codigo\": $id, \"carrera\": \"$nombre_carrera\",";
  if ($mysql->affected_rows > 0) {
    $estudiantes = $estudiantes . "\"data\": [";
    while ($row = $result->fetch_assoc()) {
      $estudiantes = $estudiantes . json_encode($row);
      $estudiantes = $estudiantes . ",";
    }
    $estudiantes = $estudiantes . "]";
  }

  $estudiantes = trim($estudiantes);
  $estudiantes = $estudiantes . "}";

  $result->close();
  return $estudiantes;
}

/**
 * Obtiene todas los estudiantes
 */
function get_all_estudiantes(mysqli $mysql): string {
  $result = $mysql->query("SELECT * FROM estudiante;");

  $estudiantes = "";
  if ($mysql->affected_rows > 0) {
    $estudiantes = "{\"data\": [";
    while ($row = $result->fetch_assoc()) {
      $estudiantes = $estudiantes . json_encode($row);
      $estudiantes = $estudiantes . ",";
    }

    $estudiantes = trim($estudiantes);
    $estudiantes = $estudiantes . "]}";
  }

  $result->close();
  return $estudiantes;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $conn = Connection::get_instance();
  $mysql = $conn->connect();

  if (isset($_GET["codigo"])) {
    $id_estudiante = $_GET["codigo"];
    $estudiante = get_estudiante_by_code($mysql, $id_estudiante);
    echo $estudiante;
    return;
  } elseif (isset($_GET["id_carrera"])) {
    $id_carrera = $_GET["id_carrera"];
    $estudiantes = get_estudiante_by_carrera($mysql, $id_carrera);
    echo $estudiantes;
    return;
  }

  $estudiantes = get_all_estudiantes($mysql);
  echo $estudiantes;

  $conn->disconnect();
}
