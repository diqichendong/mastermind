<!DOCTYPE html>
<?php
include "funciones.php";

session_start();

//Abrimos el ranking
if (isset($_GET["ranking"])) {
  header("Location: ranking.php");
}

//Una vez se introduce el nombre se prepara la partida
if (isset($_GET["nombre"])) {
  $nombre_original = $_GET["nombre"];
  $nombre = str_replace(" ", "", $nombre_original);
  $puntos = obtenerPuntos($nombre_original);
  $_SESSION["puntos"] = $puntos;
  $_SESSION["nombre_original"] = $nombre_original;
  $_SESSION["nombre"] = $nombre;

  //Comprobar si hay una partida guardada
  if (!isset($_COOKIE[$nombre])) {
    setcookie($nombre, "partidas/$nombre.txt", time() + (60 * 60 * 24 * 365));
    header("Location: mastermind.php?primera_visita=true");
  }

}


?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/mastermind.css">
  <title>MASTERMIND</title>
</head>

<body>
  <h1>MASTERMIND</h1>
  <?php
  //Primera vez que se ejecuta (elegir Jugar o Ranking)
  if (!isset($_GET["jugar"]) && !isset($nombre)) {
  ?>
  <form>
    <input type="submit" name="jugar" value="Jugar" />
    &emsp;
    <input type="submit" name="ranking" value="Ver ranking" />
  </form>
  <?php
  }

  //Segunado vez que se ejecuta (Introducir nombre)
  if (isset($_GET["jugar"]) && !isset($nombre)) {
  ?>
  <p>Introduce tu nombre y dale a jugar</p>
  <form>
    <input type="text" name="nombre" required />
    <input type="submit" value="JUGAR" />
  </form>
  <?php
  }

  //Si hay una partida guardada, se ejecuta una tercera vez (Nueva partida o Continuar)
  if (isset($_GET["nombre"]) && isset($_COOKIE[$nombre])) {
  ?>
  <p>Hay una partida guardada. ¿Desea Continuarla?</p>
  <form action="mastermind.php">
    <input type="submit" name="continuar" value="Continuar" />
    &emsp;
    <input type="submit" name="nueva_partida" value="Nueva partida" />
  </form>
  <?php
  }

  ?>
</body>

</html>