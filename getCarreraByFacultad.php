<?php
include_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $conn = Connection::get_instance();
  $mysql = $conn->connect();

  $query = $mysql->query("SELECT * FROM facultad;");
  $json = "";

  if ($mysql->affected_rows > 0) {
    $json = "{\"data\": [";
    while ($facultad = $query->fetch_assoc()) {
      $carreras_query = $mysql->query(
        "SELECT * FROM carrera WHERE id_facultad = " . $facultad["idfacultad"],
      );

      $carreras_arr = [];
      while ($carrera = $carreras_query->fetch_assoc()) {
        $carreras_arr[] = $carrera;
      }

      $facultad["carreras"] = $carreras_arr;

      $json = $json . json_encode($facultad);
      $json = $json . ",";
    }

    $json = trim($json);
    $json = $json . "]}";
  }

  echo $json;

  $query->close();
  $conn->disconnect();
}
