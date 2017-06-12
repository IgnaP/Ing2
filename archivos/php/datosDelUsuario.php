<?php
  session_start();
  require("conexionBD.php");
  conectarse($conexion);
  $email=$_SESSION["usuario"];
  $consulta="SELECT * FROM Usuarios WHERE Email='$email'";
  $resultado=mysqli_query($conexion,$consulta);
  $fila=mysqli_fetch_row($resultado);
  $ID=$fila[0];
  $nom=$fila[3];
  $ap=$fila[4];
  $fn=$fila[5];
  $tel=$fila[6];
  $preID=$fila[7];
  $resp=$fila[8];
  $pRep=$fila[12];
  $creditos=$fila[13];
  $img=$fila[14];
  $fechaN=date("d/m/Y", strtotime($fn));

  #Verifico si el usuario debe calificacion.
  $sql= "SELECT * FROM `publicaciones`,`calificaciones` WHERE `publicaciones`.`ID` =`calificaciones`.`ID_publicacion`
  AND `calificaciones`.`calificacion` IS NULL AND `publicaciones`.`usuario`=$ID";
  $result=mysqli_query($conexion, $sql);
  $cant=mysqli_num_rows($result);
  if($cant>1)
    $debe=true;
    else {
  $debe=false;
}

  $sql = "SELECT `Pregunta` FROM `preguntas` WHERE `ID`='$preID'";
  $result=mysqli_query($conexion, $sql);
  $row = mysqli_fetch_row($result);
  $pre=$row[0];
  $cons="SELECT `Nombre` FROM `reputacion` WHERE `Puntos`<='$pRep' OR `Puntos`<=-1";
  $result=mysqli_query($conexion,$cons);
  $cant=mysqli_num_rows($result);
  if ($cant == 1) {
    $row=mysqli_fetch_row($result);
    $rep=$row[0];
  } else {
    $cont=0;
    while ($row=mysqli_fetch_row($result)) {
      if (++$cont == $cant) {
        $rep=$row[0];
      }
    }
  }
  #Consultar precio de los creditos
  $sql = "SELECT `Precio` FROM `credito` WHERE `ID_credito`='1'";
  $result=mysqli_query($conexion, $sql);
  $row = mysqli_fetch_row($result);
  $precioCredito=$row[0];

  if ( isset($_GET["datos"]) ) {
    if ( $_GET["datos"]=="devolver" ) {
      $arreglo = array('nom' => "$nom", 'ap' => "$ap", 'fn' => "$fechaN", 'tel' => "$tel",
       'pRep' => "$pRep", 'creditos' => "$creditos", 'rep' => "$rep", 'fn2' => "$fn", 'resp' => "$resp",
        'pre' => "$pre", 'ID' => "$ID", 'email' => "$email", 'precioCreditos' => "$precioCredito",
         'imagen' => "$img", 'caliPendiente'=> "$debe");

      $jDatos = json_encode($arreglo);
      echo $jDatos;
    }
  }
?>