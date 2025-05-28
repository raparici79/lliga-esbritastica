<?php
/************************************************
*	File: 	fer_equip2.php						*
*	Desc: 	alineacio alternativa				*
*	Author:	Rubén Aparici 						*
************************************************/

session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];
$jornada = getJornadaActual();

if (!($userId = conValidat()))
	header("Location: login1.php?url=fer_equip2.php");

$ok = "nada";

if (foraLimit()) {
	$ok = "S'HA TANCAT EL PLAÇ PER A FER CANVIS!";
}

$jornadaActual = getJornadaActual();	
$jornada = getJornadaActual();
$infoCon = getInfoConcursant($userId); 

if ($_POST["numJugadors"] > 0 && !foraLimit()) { // supose que serà 22, ja que és el número de jugadors que té un usuari (getJugadorsPerPersona)
	$numJugadors = (int) $_POST["numJugadors"];
	$c = (int) $_POST["con"];
	$contador[1] = 0; //porters
	$contador[2] = 0; //defenses
	$contador[3] = 0; //mitjos
	$contador[4] = 0; //davanters
	$titulars = 0; 
	$selec = null;
	
	for ($i = 0 ; $i < $numJugadors ; $i++) {
		$jId = $_POST["j" . $i];
		$selec[$i] = $_POST["sel" . $i];
		if ($selec[$i] != "") {
			$titulars++;
			$posicio = consultarPosicio($jId);
			//echo "posicio-" . $i . "= " . $posicio;
			$contador[$posicio]++;
		}
		else $selec[$i] = 0; // per a marcar com a suplent els no seleccionats en el check
	}
	
	$ok = comprobarAlineacio($contador, $titulars); //ací comprovarem que el total de seleccionats=1 es 11. En tal cas, comprovarem defenses >=3 <=6, etc..
	if ($ok == "ALINEACIO GUARDADA CORRECTAMENT") {
		$estra = $contador[2]*100 + $contador[3]*10 + $contador[4]; //per exemple 300 + 40 + 3 = 343
		// echo "estrategia: " . $estra . "   ";
		
		// echo "porters = " . $contador[1] . "   ";
		// echo " defenses = " . $contador[2] . "   ";
		// echo " mitjos = " . $contador[3] . "   ";
		// echo " davanters = " . $contador[4] . "   ";		
	
		canviarEstrategia($c, $jornada, $estra);
		for ($i = 0 ; $i < $numJugadors ; $i++) {
			$jId = $_POST["j" . $i];
			$seleccionat = $selec[$i];
			if ($seleccionat == 1) {EscriuLOG ('seleccio', $jId, $seleccionat, $infoCon["nom"], 'fer_equip2');}
			updateAlineacio2($c, $jId, $jornada, $seleccionat); //actualitza jugadors_elegits per concursant, jornada, jugador, seleccionat
		}
	}
	if ($ok == "ATENCIÓ: Has alineat menys de 11 jugadors!") {

		for ($i = 0 ; $i < $numJugadors ; $i++) {
			$jId = $_POST["j" . $i];
			$seleccionat = $selec[$i];
			if ($seleccionat == 1) {EscriuLOG ('seleccio', $jId, $seleccionat, $infoCon["nom"], 'fer_equip2');}
			updateAlineacio2($c, $jId, $jornada, $seleccionat); //actualitza jugadors_elegits per concursant, jornada, jugador, seleccionat
		}
	}
	//else { 
	//	echo $ok; //en ok, si no retornem "1", retornarem un missatge de l'error concret.
	//}
}

$aJugadors = getJugadorsPerPersona($userId, $jornada);

$jornadaLFP = $jornadaActual + 4;
$enfrontLFP = getEnfrontamentsLFP($jornadaLFP);
?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />

</head>
<body>
<table border="1" class="moduletable5" align="center" width="100%">
<tr>
<?php for ($it = 0 ; $it < 10 ; $it++) { ?>
	<td width="10%" height="40"><center>
	<?php 
		echo "<img align=center img src='LFP_mini/".$enfrontLFP[$it]["idEquip1"].".png' border='0'>"; 		
	?>
	</center>
	</td>
<?php } ?>	
</tr>

<tr>
<?php for ($it = 0 ; $it < 10 ; $it++) { ?>
	<td width="10%" height="40"><center>
	<?php 
		echo "<img align=center img src='LFP_mini/".$enfrontLFP[$it]["idEquip2"].".png' border='0'>"; 		
	?>
	</center>
	</td>
<?php } ?>	
</tr>

</table>
<?php 
if ($ok != "nada") {
	if ($ok == "ALINEACIO GUARDADA CORRECTAMENT") 
		echo "<font color=\"#006600\" size=\"2\"><b>" . $ok . "</b></font>";
	else
		echo "<font color=\"#FF0000\" size=\"2\"><b>" . $ok . "</b></font>";
}
 ?>
<form name="formEdit" action="fer_equip2.php?c=<?= $userId ?>" method="post">
<input type="hidden" name="numJugadors" value="<?= sizeof($aJugadors) ?>">
<input type="hidden" name="con" value="<?= $userId ?>">

<?php
if (sizeof($aJugadors) > 0) {
?>
	<table border="1" class="moduletable" align="center" width="100%">
	<th colspan="4"><?php echo utf8_encode($infoCon["nom_equip"]); ?></th>
	<tr><td>Porters</td><td>Defenses</td><td>Mitjos</td><td>Davanters</td></tr>
	<tr>
	<td width="25%">
	<?php 
	for ($i = 0 ; $i < sizeof($aJugadors) ; $i++) {
		if ($aJugadors[$i]["posicio"] == 1) { //Porters
			if ($aJugadors[$i]["seleccionat"] == 1) { ?>
				<input type="checkbox" name="sel<?= $i ?>" value="1"  checked="ckecked">
				<?php } 
			else { ?>
				<input type="checkbox" name="sel<?= $i ?>" value="1">
				<?php } ?>
				
		<input type="hidden" name="j<?= $i ?>" value="<?= $aJugadors[$i]["id"] ?>">
		<img src='LFP_sigles/<?=$aJugadors[$i]["sigles"]?>.png' border='0'>
		<?=utf8_encode($aJugadors[$i]["nom"]); ?> <br><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	for ($i = 0 ; $i < sizeof($aJugadors) ; $i++) {
		if ($aJugadors[$i]["posicio"] == 2) { //Defenses
		//$nomJug = strtoupper($aJugadors[$i]["nom"]);
			if ($aJugadors[$i]["seleccionat"] == 1) { ?>
				<input type="checkbox" name="sel<?= $i ?>" value="1"  checked="ckecked">
				<?php } 
			else { ?>
				<input type="checkbox" name="sel<?= $i ?>" value="1">
				<?php } ?>
		<input type="hidden" name="j<?= $i ?>" value="<?= $aJugadors[$i]["id"] ?>">
		<img src='LFP_sigles/<?=$aJugadors[$i]["sigles"]?>.png' border='0'>
		<?=utf8_encode($aJugadors[$i]["nom"]); ?> <br><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	for ($i = 0 ; $i < sizeof($aJugadors) ; $i++) {
		if ($aJugadors[$i]["posicio"] == 3) { //Mitjos
		//$nomJug = strtoupper($aJugadors[$i]["nom"]);
			if ($aJugadors[$i]["seleccionat"] == 1) { ?>
				<input type="checkbox" name="sel<?= $i ?>" value="1"  checked="ckecked">
				<?php } 
			else { ?>
				<input type="checkbox" name="sel<?= $i ?>" value="1">
				<?php } ?>
		<input type="hidden" name="j<?= $i ?>" value="<?= $aJugadors[$i]["id"] ?>">
		<img src='LFP_sigles/<?=$aJugadors[$i]["sigles"]?>.png' border='0'>
		<?=utf8_encode($aJugadors[$i]["nom"]); ?> <br><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	for ($i = 0 ; $i < sizeof($aJugadors) ; $i++) {
		if ($aJugadors[$i]["posicio"] == 4) { //Davanters
		//$nomJug = strtoupper($aJugadors[$i]["nom"]);
			if ($aJugadors[$i]["seleccionat"] == 1) { ?>
				<input type="checkbox" name="sel<?= $i ?>" value="1"  checked="ckecked">
				<?php } 
			else { ?>
				<input type="checkbox" name="sel<?= $i ?>" value="1">
				<?php } ?>
		<input type="hidden" name="j<?= $i ?>" value="<?= $aJugadors[$i]["id"] ?>">
		<img src='LFP_sigles/<?=$aJugadors[$i]["sigles"]?>.png' border='0'>
		<?=utf8_encode($aJugadors[$i]["nom"]); ?> <br><br>
	<?php
		}
	}
	?>
	</td>
	</tr>
	</table>
	
		<table border="0" class="moduletable" align="center" width="100%">
		<?php
		putenv('TZ=Europe/Madrid');  //Per a obtindre l'hora espanyola
		$data = strftime("%d/%m/%Y", time());
		$hora = date("-H:i",time());
		?>
		<div align="center"><font color="#990000" face="Courier New"><b>Data límit: <?= getValorConfiguracio("data_limit") ?></b></font></div>
		<? //date("H:i") ?>
		</table>	
	
	<table border="0" class="moduletable" align="center" width="100%">
	<div align="center"><input type="submit" value="Guardar"></div>
<?php
	}
	else
		echo "No hi ha Jugadors disponibles.";
?>
	</table>
</form>
<br>

<!--
<br><br>
 <div class="back_button"><a href="login1.php?tancar=si">Tancar</a></div> --->
</body>
</html>
