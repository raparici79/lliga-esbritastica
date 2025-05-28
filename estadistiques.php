<?php
/************************************************
*	File: 	estadistiques.php					*
*	Desc: 	estadistiques curioses	 			*
*	Author:	Jose Gargallo 						*
************************************************/

session_start();
include("config.php");
include("funcions.php");

$userType = $_SESSION["usertype"];
$jornada = getJornadaActual();
if ($jornada == 0 && $userType != "Super Administrator")
{
echo "PILLIN PILLIN!!!!";
exit;
}

$aCon = getTaula("concursants", "usuari");

$jMes = null;
$estrelles = null;
//$estrelles = getPossiblesEstrelles();
$jMes = getMesElegits();
$estrelles = getPossiblesEstrelles();
$maxMin = getMaxMinPunts();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<link href="../templates/rhuk_solarflare_ii/css/template_css.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
</head>
<body>
<div class="back_button"><a href="consultajug.php">Buscar Jugador</a></div>
<br><br><br>
<table border="1" class="moduletable" align="center" width="100%">
<th colspan="5"><div align="center">Màxima i Mínima Puntuació</div></th>
<tr><td width="10%"></td><td width="20%"><b>Nom</b></td><td width="50%"><b>Equip</b></td><td width="10%"><b>Punts</b></td><td width="10%"><b>Jornada</b></td></tr>
<tr><td><b>Màxim:</b></td><td><?= utf8_encode($maxMin[0]["nom"]); ?></td><td><?= utf8_encode($maxMin[0]["nom_equip"]); ?></td><td><?= $maxMin[0]["punts"] ?></td><td><?= $maxMin[0]["jornada"] ?></td></tr>
<tr><td><b>Mínim:</b></td><td><?= utf8_encode($maxMin[1]["nom"]); ?></td><td><?= utf8_encode($maxMin[1]["nom_equip"]); ?></td><td><?= $maxMin[1]["punts"] ?></td><td><?= $maxMin[1]["jornada"] ?></td></tr>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">Jugadors més elegits</div></th>
<tr><td width="40%"><b>Nom</b></td><td width="30%"><b>Equip</b></td><td width="30%"><b>Vegades elegit</b></td></tr>
<?php for ($i = 0; $i < sizeof($jMes); $i++) {?>
<tr><td><?= utf8_encode($jMes[$i]["nom"]); ?></td><td><?= $jMes[$i]["sigles"] ?></td><td><?= $jMes[$i]["num"] ?></td></tr>
<?php } ?>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">Els Favorits</div></th>
<tr><td width="40%"><b>Nom</b></td><td width="30%"><b>Favorit</b></td><td width="30%"><b>Vegades Titular</b></td></tr>
<?php for ($i = 0; $i < sizeof($aCon); $i++) {
	$fav = getFavorit($aCon[$i]["id"]);
	if ($fav != null) {
?>
<tr><td><?= utf8_encode($fav["usuari"]); ?></td><td><?= utf8_encode($fav["nom"]); ?></td><td><?= $fav["num"] ?></td></tr>
<?php 
	}
} ?>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">Possibles jugadors Estrella</div></th>
<tr><td width="20%"><b>Nom</b></td><td width="55%"><b>Equip</b></td><td width="25%"><b>Jugadors</b></td></tr>
<?php 
$anterior = null;
$i = 0;

while ( $i < sizeof($estrelles)) {
	$anterior = $estrelles[$i]["nom_con"];
?>
<tr>
<td><?= utf8_encode($estrelles[$i]["nom_con"]); ?></td><td><?= utf8_encode($estrelles[$i]["nom_equip"]); ?></td>
<td>
<?php if ($estrelles[$i]["ecom"] == 1) { ?><i><font color="#B40404"><?php } ?>
<?= utf8_encode($estrelles[$i]["nom"]) . " (" . $estrelles[$i]["sigles"] . ")" ;?>
</font></i>
<?php 
	$i++;
	while ( $i < sizeof($estrelles) && $anterior == $estrelles[$i]["nom_con"]) {
	if ($estrelles[$i]["ecom"] == 1) { ?><i><font color="#B40404"><?php } 
		echo "<br>" . utf8_encode($estrelles[$i]["nom"]) . " (" . $estrelles[$i]["sigles"] . ")";
		?> </font></i> <?php
		$i++;
	}
?>
</td>
</tr>
<?php 
} 
?>
</table>



</body>
</html>