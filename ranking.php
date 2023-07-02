<!DOCTYPE html>
<?php
include "funciones.php";

//Mostrar un ranking ordenado por puntos acumulados
$ranking = leerPuntos();
arsort($ranking);

?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/mastermind.css">
  <title>Ranking</title>
</head>

<body>
  <h1>RANKING</h1>
  <table>
    <tr>
      <th>Nombre</th>
      <th>Puntos</th>
    </tr>
    <?php
    //Mostrar ranking si no está vacío
    if (!empty($ranking)) {
      foreach ($ranking as $nombre => $puntos) {
        echo "<tr>";
        echo "<td>" . $nombre . "</td>";
        echo "<td>" . $puntos . "</td>";
        echo "</tr>";
      }
    }
    ?>
  </table>
  <form action="index.php">
    <input type="submit" value="Inicio" />
  </form>
</body>

</html>