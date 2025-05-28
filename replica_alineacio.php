<?php
/************************************************
*	File: 	replica_alineacio.php		*
*	Desc: 	replica l'alineacio de la jorna anterior	*
*	Author:	Rubén Aparici 			*
************************************************/
session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];

if (!($userId = conValidat()) || $userType == "Registered")
	header("Location: login1.php?url=replica_alineacio.php");
	
$jornadaActual = getJornadaActual();

if ((isset($_POST["con"]) && $_POST["con"] > 0) && ($userType == "Administrator" || $userType == "Super Administrator")) {
 //actualitzem 
	$concursant = $_POST["con"];
	$estrategia = $_POST["est"];
	canviarEstrategia($concursant, $jornadaActual, $estrategia); 
	updateAlineacio($concursant, $jornadaActual);
	header("Location: fer_equip.php?c=$concursant"); // fer_equip?c=$concursant	//header("Location: fer_equip.php?estrat=$estrategia&c=$concursant"); // fer_equip?c=$concursant
}
$c = (int) $_GET["c"];
?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->

</head>
<body>

<table border="1" class="moduletable" align="center" width="300">
<tr>

<td width="100%" valign="top">
	<form name="formEdit" action="replica_alineacio.php" method="post">
	<table align="center" width="95%">
<?php
	if ($userId > 0) {

	$jornadaAnterior = $jornadaActual - 1;
	$jugadors22 = getJugadorsPerPersona($c, $jornadaAnterior);
	
	$i=0;
	$alineacio = "(Jornada ".$jornadaAnterior.") \n";
	$num_def = 0;
	$num_mig = 0;
	$num_del = 0;
	for ($j = 0; $j < sizeof($jugadors22); $j++) {
	 if ($jugadors22[$j]["seleccionat"] == 1){
		$i++;
	     $alineacio .= "  ".$i .".- ".$jugadors22[$j]["nom"]." (".$jugadors22[$j]["sigles"].")"."\n";   //obtenim la cadena de jugadors
		if ($jugadors22[$j]["posicio"] == 2) {$num_def++;}
		if ($jugadors22[$j]["posicio"] == 3) {$num_mig++;}
		if ($jugadors22[$j]["posicio"] == 4) {$num_del++;} 
		}
	}
	$estra = $num_def*100 + $num_mig*10 + $num_del;
?>
	<tr><br>Vas a guardar aquesta alineació (<?= $estra ?>):<br><br>
	
	<b>Alineació </b> <?=nl2br($alineacio) ?><br>
		<input type="hidden" name="est" value="<?= $estra ?>">
		<input type="hidden" name="con" value="<?= $c ?>">
	</tr>
		<tr><td colspan="3"><div align="left"><input type="submit" value="Guardar"></div></td></tr>
<?php
	}
	else	{
		?>
		<tr><td><a href="fer_equip.php ?>">Tornar</a></td></tr>
		<?php
		}
		?>
	</table>
	</form>
</td>
</tr>
</table>

</body>
</html>