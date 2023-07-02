<!DOCTYPE html>
<?php
include "funciones.php";

session_start();

//Obtener los datos que se van a mostrar.
if (isset($_SESSION["nombre_original"])) {
  $nombre = $_SESSION["nombre_original"];
  if (isset($_GET["hasGanado"])) {
    //Caso ganador
    $puntos = 11 - sizeof($_SESSION["intentos"]);
    $puntos_acumulados = $_SESSION["puntos"] + $puntos;
    insertarPuntos($nombre, $puntos_acumulados);
  }
} else {
  //Cuando se pulsa el boton de Inicio
  header("Location: index.php");
}

//Se elimina los datos de la última partida y la sesión
setcookie($_SESSION["nombre"], "", time() - 1);
session_destroy();

?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/mastermind.css">
  <title>Resultado</title>
</head>

<body>
  <?php
  //Vista de ganador
  if (isset($_GET["hasGanado"])) {
  ?>
  <h1>¡HAS GANADO, <?= $nombre; ?>!</h1>
  <h3>Has conseguido <?= $puntos; ?> puntos en esta partida.</h3>
  <h3>Acumulas <?= $puntos_acumulados; ?> puntos en total.</h3>
  <?php
  }
  ?>

  <?php

  //Vista de perdedor
  if (isset($_GET["hasPerdido"])) {
  ?>
  <h1>¡HAS PERDIDO, <?= $nombre; ?>!</h1>
  <h3>Suerte en la próxima vez.</h3>
  <?php
  }
  ?>

  <form>
    <input type="submit" value="Inicio" />
  </form>
</body>

</html>