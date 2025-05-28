<?php
/************************************************
*	File: 	guardar_alineacio.php				*
*	Desc: 	Guarda un log de l'alineació feta   *
*	Author:	Rubén Aparici 						*
************************************************/
session_start();

include("config.php");
include("funcions2.php");


$userType = $_SESSION["usertype"];
if (!($userId = conValidat()) || $userType == "Registered")
	header("Location: login1.php?url=guardar_alineacio.php");

$jornadaActual = getJornadaActual();


$c1 = getInfoConcursant($_GET["id1"]); //ve de userId sel·leccionat (en cas de que estiga tocant un administrador)
$c2 = getInfoConcursant($_GET["id2"]); //ve de userId2 que és l'usuari que està conectat
$jornada = $_GET["j"];

?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
</head>
<body>


<?php 

putenv('TZ=Europe/Madrid');  //Per a obtindre l'hora espanyola
$data = strftime("%d/%m/%Y", time());
$hora = date("H:i:s",time());

$jugadors22 = getJugadorsPerPersona($c1["id"], $jornada);

$i=0;
$alineacio = "(Jornada ".$jornada.") \n";
for ($j = 0; $j < sizeof($jugadors22); $j++) {
 if ($jugadors22[$j]["seleccionat"] == 1){
	$i++;
     $alineacio .= "  ".$i .".- ".$jugadors22[$j]["nom"]." (".$jugadors22[$j]["sigles"].")"."\n";   //obtenim la cadena de jugadors
}
}
$msg = InsertaLog($c1["nom"], $c2["nom"], $alineacio, $data, $hora);

if ($msg){
?>

Has guardat una copia amb aquestes dades:<br><br>

<b>Concursant:</b> <?= $c1["nom"] ?><br>
<b>Usuari_mod:</b> <?=$c2["nom"] ?><br>
<b>Data:</b> <?=$data ?><br>
<b>Hora:</b> <?=$hora ?><br><br>
<b>Alineació </b> <?=nl2br($alineacio) ?><br>

<?php 
if ($i < 11){
?>
<font color="#FF0000"><b> ATENCIÓ! NO HAS ALINEAT 11 JUGADORS! </b></font><br><br>
<?php 
}
?>
Aquestes dades sols servixen com a prova de que s'ha fet l'alineació en cas de que es produira un error en l'11 penjat en la web. 
En un principi no tenen cap tipus de validesa.

<?php 
}
else{
?>
S'ha produit un error al copiar en el log la teua alineacio.
<?php 
}
?>
</body>
</html>
