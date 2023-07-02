<!DOCTYPE html>
<?php
include "funciones.php";

session_start();

//Obtenemos los datos del jugador en la sesión y en la cookie para utilizarlos.
$nombre_original = $_SESSION["nombre_original"];
$nombre = $_SESSION["nombre"];
$puntos = $_SESSION["puntos"];
$path_partida = $_COOKIE[$nombre];

//Se ha pulsado en "Borrar datos"
if (isset($_GET["borrar_datos"])) {
  borrarDatos($nombre_original);
  setcookie($nombre, "", time() - 1);
  session_destroy();
  header("Location: index.php");
}

//Se ha pulsado en "Volver al menú"
if (isset($_GET["volver_menu"])) {
  session_destroy();
  header("Location: index.php");
}

//Primera visita o Nueva partida
if (isset($_GET["primera_visita"]) || isset($_GET["nueva_partida"])) {
  $combinacion_secreta = generarCombinacion();
  $intentos = [];
  $pistas = [];

} else {
  //Si no es primera visita o nueva partida, comprobar se quiere continuar una partida
  if (isset($_GET["continuar"])) {
    $partida = cargarPartida($path_partida);
    $combinacion_secreta = $partida[0];
    $intentos = $partida[1];
    $pistas = $partida[2];

  } else {
    //Obtener de la sesión los datos de la partida actual.
    $combinacion_secreta = $_SESSION["combinacion_secreta"];
    $intentos = $_SESSION["intentos"];
    $pistas = $_SESSION["pistas"];
    $intento = isset($_GET["intento"]) ? $_GET["intento"] : null;

    //Tratamos los intentos.
    if ($intento != null) {
      array_push($pistas, generarPista($intento, $combinacion_secreta));
      array_push($intentos, $intento);
    }

    //Comprobar si se ha ganado
    if ($intento === $combinacion_secreta) {
      header("Location: resultado.php?hasGanado=1");
    }

    //Comprobar si se ha perdido
    if (sizeof($intentos) == 10) {
      header("Location: resultado.php?hasPerdido=1");
    }

  }

}

//Guardar datos de la partida en la sesión y en un fichero
$_SESSION["nombre"] = $nombre;
$_SESSION["combinacion_secreta"] = $combinacion_secreta;
$_SESSION["intentos"] = $intentos;
$_SESSION["pistas"] = $pistas;
guardarPartida($path_partida, $combinacion_secreta, $intentos, $pistas);


?>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MASTERMIND</title>
  <link rel="stylesheet" href="css/mastermind.css">
</head>

<body>
  <h1>MASTERMIND</h1>
  <h2>Jugador: <?= $nombre_original ?>&emsp;&emsp;Puntos acumulados: <?= $puntos ?>
  </h2>
  <!-- Solo para depurar -->
  <?php //var_dump($combinacion_secreta) ?>
  <!----------------------->
  <table>
    <tr>
      <th></th>
      <th colspan="5">INTENTOS</th>
      <th></th>
      <th colspan="5">PISTAS</th>
    </tr>
    <?php
    //Mostrar los intentos
    if (!empty($intentos)) {
      for ($i = 0; $i < sizeof($intentos); $i++) {
        echo "<tr>";
        echo "<th>" . ($i + 1) . "</th>";
        foreach ($intentos[$i] as $dato) {
          echo "<td class='$dato'></td>";
        }
        echo "<td></td>";
        rsort($pistas[$i]);
        for ($j = 0; $j < 5; $j++) {
          if (isset($pistas[$i][$j])) {
            echo "<td class='" . $pistas[$i][$j] . "'></td>";
          } else {
            echo "<td></td>";
          }
        }

        echo "</tr>";
      }
    }
    ?>
  </table>
  <br>
  <p>Introduce la combinación ganadora por <?=(10 - sizeof($intentos)) ?> puntos:</p>
  <!--Cada option guarda el último intento-->
  <form action="<?= $_SERVER["PHP_SELF"] ?>">
    <span>Elige una combinación colores:</span><br>
    <select name="intento[]" onchange="this.className=this.options[this.selectedIndex].className"
      class="<?php echo ((!empty($intentos)) ? $intentos[sizeof($intentos) - 1][0] : "rojo"); ?>">
      <option value="rojo" class="rojo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][0]=="rojo") ?
        "selected" : ""); ?>
        >Rojo</option>
      <option value="amarillo" class="amarillo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][0]=="amarillo") ? "selected" : ""); ?>>Amarillo</option>
      <option value="naranja" class="naranja" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][0]=="naranja") ? "selected" : ""); ?>>Naranja</option>
      <option value="azul" class="azul" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][0]=="azul") ?
        "selected" : ""); ?>
        >Azul</option>
      <option value="verde" class="verde" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][0]=="verde") ? "selected" : ""); ?>
        >Verde</option>
      <option value="rosa" class="rosa" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][0]=="rosa") ?
        "selected" : ""); ?>
        >Rosa</option>
      <option value="morado" class="morado" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][0]=="morado") ? "selected" : ""); ?>
        >Morado</option>
      <option value="marron" class="marron" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][0]=="marron") ? "selected" : ""); ?>
        >Marrón</option>
    </select>

    <select name="intento[]" onchange="this.className=this.options[this.selectedIndex].className"
      class="<?php echo ((!empty($intentos)) ? $intentos[sizeof($intentos) - 1][1] : "rojo"); ?>">
      <option value="rojo" class="rojo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][1]=="rojo") ?
        "selected" : ""); ?>
        >Rojo</option>
      <option value="amarillo" class="amarillo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][1]=="amarillo") ? "selected" : ""); ?>>Amarillo</option>
      <option value="naranja" class="naranja" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][1]=="naranja") ? "selected" : ""); ?>>Naranja</option>
      <option value="azul" class="azul" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][1]=="azul") ?
        "selected" : ""); ?>
        >Azul</option>
      <option value="verde" class="verde" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][1]=="verde") ? "selected" : ""); ?>
        >Verde</option>
      <option value="rosa" class="rosa" class="rosa" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][1]=="rosa") ? "selected" : ""); ?>>Rosa</option>
      <option value="morado" class="morado" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][1]=="morado") ? "selected" : ""); ?>>Morado</option>
      <option value="marron" class="marron" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][1]=="marron") ? "selected" : ""); ?>>Marrón</option>
    </select>

    <select name="intento[]" onchange="this.className=this.options[this.selectedIndex].className"
      class="<?php echo ((!empty($intentos)) ? $intentos[sizeof($intentos) - 1][2] : "rojo"); ?>">
      <option value="rojo" class="rojo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][2]=="rojo") ?
        "selected" : ""); ?>
        >Rojo</option>
      <option value="amarillo" class="amarillo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][2]=="amarillo") ? "selected" : ""); ?>>Amarillo</option>
      <option value="naranja" class="naranja" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][2]=="naranja") ? "selected" : ""); ?>>Naranja</option>
      <option value="azul" class="azul" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][2]=="azul") ?
        "selected" : ""); ?>
        >Azul</option>
      <option value="verde" class="verde" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][2]=="verde") ? "selected" : ""); ?>
        >Verde</option>
      <option value="rosa" class="rosa" class="rosa" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][2]=="rosa") ? "selected" : ""); ?>>Rosa</option>
      <option value="morado" class="morado" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][2]=="morado") ? "selected" : ""); ?>>Morado</option>
      <option value="marron" class="marron" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][2]=="marron") ? "selected" : ""); ?>>Marrón</option>
    </select>

    <select name="intento[]" onchange="this.className=this.options[this.selectedIndex].className"
      class="<?php echo ((!empty($intentos)) ? $intentos[sizeof($intentos) - 1][3] : "rojo"); ?>">
      <option value="rojo" class="rojo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][3]=="rojo") ?
        "selected" : ""); ?>
        >Rojo</option>
      <option value="amarillo" class="amarillo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][3]=="amarillo") ? "selected" : ""); ?>>Amarillo</option>
      <option value="naranja" class="naranja" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][3]=="naranja") ? "selected" : ""); ?>>Naranja</option>
      <option value="azul" class="azul" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][3]=="azul") ?
        "selected" : ""); ?>
        >Azul</option>
      <option value="verde" class="verde" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][3]=="verde") ? "selected" : ""); ?>
        >Verde</option>
      <option value="rosa" class="rosa" class="rosa" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][3]=="rosa") ? "selected" : ""); ?>>Rosa</option>
      <option value="morado" class="morado" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][3]=="morado") ? "selected" : ""); ?>>Morado</option>
      <option value="marron" class="marron" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][3]=="marron") ? "selected" : ""); ?>>Marrón</option>
    </select>

    <select name="intento[]" onchange="this.className=this.options[this.selectedIndex].className"
      class="<?php echo ((!empty($intentos)) ? $intentos[sizeof($intentos) - 1][4] : "rojo"); ?>">
      <option value="rojo" class="rojo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][4]=="rojo") ?
        "selected" : ""); ?>
        >Rojo</option>
      <option value="amarillo" class="amarillo" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][4]=="amarillo") ? "selected" : ""); ?>>Amarillo</option>
      <option value="naranja" class="naranja" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][4]=="naranja") ? "selected" : ""); ?>>Naranja</option>
      <option value="azul" class="azul" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) - 1][4]=="azul") ?
        "selected" : ""); ?>
        >Azul</option>
      <option value="verde" class="verde" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][4]=="verde") ? "selected" : ""); ?>
        >Verde</option>
      <option value="rosa" class="rosa" class="rosa" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][4]=="rosa") ? "selected" : ""); ?>>Rosa</option>
      <option value="morado" class="morado" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][4]=="morado") ? "selected" : ""); ?>>Morado</option>
      <option value="marron" class="marron" <?php echo ((!empty($intentos) && $intentos[sizeof($intentos) -
        1][4]=="marron") ? "selected" : ""); ?>>Marrón</option>
    </select>

    <input type="submit" value="Enviar combinación">
  </form>
  <br>
  <form>
    <input type="submit" name="volver_menu" value="Volver al menú">
  </form>
  <br>
  <form>
    <input type="submit" name="borrar_datos" value="Borrar datos">
  </form>
</body>

</html>