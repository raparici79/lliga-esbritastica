<?php
/************************************************
*	File: 	punts_jugadors.php					*
*	Desc: 	Llista els jugadors d'un concursant	*
*	Author:	Jose Gargallo 						*
************************************************/

include("config.php");
include("funcions.php");

$con = getInfoConcursant($_GET["c"]);
$aJugadors = getPuntsJugadorsPerConcursant($_GET["c"], $_GET["jo"]);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />

</head>
<body>


<table class="moduletable">
	<th align="center" colspan="6"><?= utf8_encode($con["nom_equip"]); ?>, <?= utf8_encode($con["nom"]); ?> (Jornada <?= $_GET["jo"] ?>)</th>
	<tr><td><b>Nom</b></td><td><b>Equip</b></td><td><b>Valor</b></td><td><b>Posició</b></td><td><b>Extracom.</b></td><td><b>Punts</b></td></tr>
<?php
		$totJornada = 0;
		for($i = 0; $i < sizeof($aJugadors); $i++) {
?>
		<tr>
			<td><?=utf8_encode($aJugadors[$i]["nom"]); ?></td>
			<td><?=$aJugadors[$i]["sigles"] ?></td>
			<td><?=$aJugadors[$i]["valor"] ?></td>
			<td><?=$POSICIONS[$aJugadors[$i]["posicio"]] ?></td>
			<td align="center"><?=$ECOMUNITARI[$aJugadors[$i]["ecomunitari"]] ?></td>
			<td><?=$aJugadors[$i]["punts"] ?></td>
		</tr>
<?php 
		$totJornada += $aJugadors[$i]["punts"];
		} 
?>
		<tr><td></td><td></td><td></td><td></td><td></td><td><b>Total: <?= $totJornada ?></b></td></tr>
</table>

<?php $aJugadors = getPuntsJugadorsPerConcursantTotal($_GET["c"], $_GET["jo"]); ?>
<table class="moduletable">
	<th align="center" colspan="6">Puntuacions Totals</th>
	<tr><td><b>Nom</b></td><td><b>Equip</b></td><td><b>Valor</b></td><td><b>Posició</b></td><td><b>Extracom.</b></td><td><b>Punts</b></td></tr>
<?php
		$totJornada = 0;
		for($i = 0; $i < sizeof($aJugadors); $i++) {
?>
		<tr>
			<td><?=utf8_encode($aJugadors[$i]["nom"]); ?></td>
			<td><?=$aJugadors[$i]["sigles"] ?></td>
			<td><?=$aJugadors[$i]["valor"] ?></td>
			<td><?=$POSICIONS[$aJugadors[$i]["posicio"]] ?></td>
			<td align="center"><?=$ECOMUNITARI[$aJugadors[$i]["ecomunitari"]] ?></td>
			<td><?=$aJugadors[$i]["punts"] ?></td>
		</tr>
<?php 
		$totJornada += $aJugadors[$i]["punts"];
		} 
?>
		<tr><td></td><td></td><td></td><td></td><td></td><td><b>Total: <?= $totJornada ?></b></td></tr>
</table>

<br>
<div class="back_button"><a href="classificacio.php?jornada=<?= $_GET["jo"] ?>">Tornar</a></div>
</body>
</html>
