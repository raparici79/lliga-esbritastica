<?php
/************************************************
*	File: 	rivals.php							*
*	Desc: 	Llista els equips dels concursants	*
*	Author:	Jose Gargallo 						*
************************************************/
session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];

$jornadaActual = getJornadaActual();

$userId = 1;
if ($_GET["c"] > 0)
	$userId = (int) $_GET["c"];
$infoCon = getInfoConcursant($userId);

$userLogged = conValidat();

$palmares = getPalmares($infoCon["usuari"] );



if ($jornadaActual == 0 && $userType != "Super Administrator")
{
echo "PILLIN PILLIN!!!!";
exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
</head>
<body>
<?php
echo "<b>Jornada $jornadaActual</b><br><br>";
$llistaCon = getTaula("concursants", "usuari");
echo "|";
for ($i = 0; $i < sizeof($llistaCon); $i++) {
	if ($userId != $llistaCon[$i]["id"]) {
?>
	<a href="rivals.php?c=<?= $llistaCon[$i]["id"] ?>"><?= utf8_encode($llistaCon[$i]["usuari"]); ?></a>|
<?php
	}else{
?>
	<?= utf8_encode($llistaCon[$i]["usuari"]); ?>|
<?php
		$nomusuari=utf8_encode($llistaCon[$i]["nom"]);
	}
}
?>

<?php 
	if (!foraLimitRivals()) {
		$jugadors = getJugadorsRival($userId, $jornadaActual);
		for ($i = 0; $i < sizeof($jugadors); $i++)
			$jugadors[$i]["seleccionat"] = 0;
	}
	else
	{
		$jugadors = getJugadorsPerPersona($userId, $jornadaActual);
	}
?>
<!--
<br>
Jugador estrella: <b><?php if ($infoCon["estrella"] > 0) echo getNomJugador($infoCon["estrella"]); else echo ""; ?></b>
<br/>
dixem de mostrar jugador estrella-->
<table border="1" class="moduletable" align="center" width="100%">
<th><div align="center"><?= utf8_encode($infoCon["nom_equip"]); ?></div></th>
<tr>
	<td><div align="center">Estratègia: <?= $infoCon["estrategia"] ?></div>
	</td>
</tr>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="4">Equip titular</th>
<tr><td>Porter</td><td>Defenses</td><td>Mijos</td><td>Davanters</td></tr>
<tr>
	<td width="25%">
	<?php 
	$porters = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 1 && $jugadors[$i]["seleccionat"] == 1) {
			$porters++;
	?>   <?php if ($jugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?>
		<?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>) </font></i><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	$defenses = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 2 && $jugadors[$i]["seleccionat"] == 1) {
			$defenses++;
	?>	<?php if ($jugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?>
		<?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>) </font></i><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	$mijos = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 3 && $jugadors[$i]["seleccionat"] == 1) {
			$mijos++;
	?>	<?php if ($jugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?>
		<?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>) </font></i><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	$davanters = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 4 && $jugadors[$i]["seleccionat"] == 1) {
			$davanters++;
	?>	<?php if ($jugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?>
		<?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>) </font></i><br>
	<?php
		}
	}
	?>
	</td>
</tr>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="4">Equip titular</th>
<tr><td>Porter</td><td>Defenses</td><td>Mijos</td><td>Davanters</td></tr>
<tr>
	<td width="25%">
	<?php 
	$porters = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 1 && $jugadors[$i]["seleccionat"] == 0) {
			$porters++;
	?>   <?php if ($jugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?>
		<?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>) </font></i><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	$defenses = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 2 && $jugadors[$i]["seleccionat"] == 0) {
			$defenses++;
	?>	<?php if ($jugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?>
		<?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>) </font></i><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	$mijos = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 3 && $jugadors[$i]["seleccionat"] == 0) {
			$mijos++;
	?>	<?php if ($jugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?>
		<?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>) </font></i><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	$davanters = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 4 && $jugadors[$i]["seleccionat"] == 0) {
			$davanters++;
	?>	<?php if ($jugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?>
		<?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>) </font></i><br>
	<?php
		}
	}
	?>
	</td>
</tr>
</table>


<table align="center" width="100%">
<tr>
<td><b>Presupost Gastat: <?php $pre=getPresupostGastat($userId);echo $pre; ?>, restant: <?= $PRESUPOST-$pre ?>.</b></td>
<td><div align="right"><a href="log_txt.php?c=<?= $userId ?>">LOG</a><b>&nbsp;/&nbsp;</b>
<a href="aliniacions_txt.php">Alineacions</a><b>&nbsp;/&nbsp;</b><a href="equips_sencers_txt.php">Equips Sencers</a></div></td>
</tr>
</table>


<br><P><font color = "#ea7e1a"><b><u>PALMARÉS</u></b></font></P>
<?php 
if (sizeof($palmares)>0){ ?>
	<table align="left">
<?php
	//$palmares = getPalmares($userId);
	$palmares = getPalmares($infoCon["usuari"] );
	
		for($i = 0; $i < sizeof($palmares); $i++) {
?>
		<tr><td><font color = "#4e3b1d"><?=$palmares[$i]["competicio"]  ?></font></td><td>&nbsp; &nbsp; </td>
		<td><font color = "#4e3b1d"><?=$palmares[$i]["any"]-1 ?>-<?=$palmares[$i]["any"] ?></font></td></tr>

<?php
		}
	?> </table>
<?php
}
else if ($_GET["c"] > 0){echo $nomusuari . " no ha guanyat cap títol. Si vols consultar els campions de la història, ";
?>
	<a href="palmares.php">Click ací</a> <?php } 
else {echo "Si vols consultar els campions de la història, "; ?> <a href="palmares.php">Click ací</a> <?php } ?>

	
</body>
</html>
