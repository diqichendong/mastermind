<?php
/**
 * Genera una combinación secreta
 */
function generarCombinacion()
{
  $combinacion = [];

  while (sizeof($combinacion) < 5) {
    $colores = ["rojo", "amarillo", "naranja", "azul", "verde", "rosa", "morado", "marron"];
    $num_random = rand(0, 7);
    if (!in_array($colores[$num_random], $combinacion)) {
      array_push($combinacion, $colores[$num_random]);
    }
  }

  return $combinacion;
}

/**
 * Carga la partida guardada de un jugador
 */
function cargarPartida($path_partida)
{
  $res = [];
  $intentos = [];
  $pistas = [];
  $fichero = fopen($path_partida, "r");

  while (!feof($fichero)) {
    $linea = explode(";", trim(fgets($fichero)));

    //Cargamos la combinación secreta
    if ($linea[0] == "combinacion_secreta") {
      $combinacion_secreta = explode(",", $linea[1]);
      continue;
    }

    //Cargamos cada intento
    if ($linea[0] == "intento") {
      array_push($intentos, explode(",", $linea[1]));
      continue;
    }

    //Cargamos cada pista
    if ($linea[0] == "pista") {
      array_push($pistas, explode(",", $linea[1]));
    }
  }

  fclose($fichero);
  array_push($res, $combinacion_secreta, $intentos, $pistas);
  return $res;
}

/**
 * Guarda la partida actual
 */
function guardarPartida($path_partida, $combinacion_secreta, $intentos, $pistas)
{
  $fichero = fopen($path_partida, "w");

  //Escribimos la combinación secreta
  fwrite($fichero, "combinacion_secreta;");
  for ($i = 0; $i < sizeof($combinacion_secreta); $i++) {
    fwrite($fichero, $combinacion_secreta[$i]);
    if ($i != 4) {
      fwrite($fichero, ",");
    }
  }
  fwrite($fichero, "\n");

  //Escribimos los intentos
  foreach ($intentos as $intento) {
    fwrite($fichero, "intento;");
    for ($i = 0; $i < sizeof($intento); $i++) {
      fwrite($fichero, $intento[$i]);
      if ($i != 4) {
        fwrite($fichero, ",");
      }
    }
    fwrite($fichero, "\n");
  }

  //Escribimos las pistas
  foreach ($pistas as $pista) {
    fwrite($fichero, "pista;");
    for ($i = 0; $i < sizeof($pista); $i++) {
      fwrite($fichero, $pista[$i]);
      if ($i != 4) {
        fwrite($fichero, ",");
      }
    }
    fwrite($fichero, "\n");
  }

  fclose($fichero);
}

/**
 * Generamos la pista
 */
function generarPista($intento, $combinacion_secreta)
{
  $pista = [];
  for ($i = 0; $i < sizeof($intento); $i++) {
    //Acierto
    if (
      in_array($intento[$i], $combinacion_secreta) &&
      $intento[$i] == $combinacion_secreta[$i]
    ) {
      array_push($pista, "negro");
      continue;
    }

    //Está en la combinación
    if (in_array($intento[$i], $combinacion_secreta)) {
      array_push($pista, "blanco");
    }
  }

  return $pista;
}

/**
 * Inserta los puntos acumulados de un jugador
 */
function insertarPuntos($nombre, $puntos)
{
  $puntos_jugadores = leerPuntos();
  $puntos_jugadores[$nombre] = $puntos;
  $fichero = fopen("puntos.txt", "w");

  foreach ($puntos_jugadores as $jugador => $puntuacion) {
    fwrite($fichero, "$jugador;$puntuacion\n");
  }

  fclose($fichero);
}

/**
 * Devuelve todos los jugadores y sus puntos
 */
function leerPuntos()
{
  $puntos = [];
  $fichero = fopen("puntos.txt", "r");

  if ($fichero != false) {
    while (!feof($fichero)) {
      $datos = explode(";", fgets($fichero));
      if (sizeof($datos) != 1) {
        $puntos[$datos[0]] = intval($datos[1]);
      }
    }
  }
  fclose($fichero);

  return $puntos;
}

/**
 * Obtener los puntos acumulados de un jugador
 */
function obtenerPuntos($nombre)
{
  $puntos = 0;
  $fichero = fopen("puntos.txt", "r");

  while (!feof($fichero)) {
    $linea = explode(";", fgets($fichero));
    if ($linea[0] == $nombre) {
      $puntos = intval($linea[1]);
    }
  }

  fclose($fichero);

  return $puntos;
}

/**
 * Borra los puntos acumulados de un jugador.
 */
function borrarDatos($nombre)
{
  $puntos = leerPuntos();
  unset($puntos[$nombre]);
  $fichero = fopen("puntos.txt", "w");

  foreach ($puntos as $jugador => $puntuacion) {
    fwrite($fichero, "$jugador;$puntuacion\n");
  }

  fclose($fichero);
}

?>