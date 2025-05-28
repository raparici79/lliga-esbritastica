<?php
/************************************************
*	File: 	llistat_jugadors.php				*
*	Desc: 	Llista els equips i jugadors.		*
*	Author:	Jose Gargallo 						*
************************************************/
session_start();
include("config.php");
include("funcions.php");

$userType = $_SESSION["usertype"];
$jornadaActual = getJornadaActual();

/*Eliminar esto quan vullgam mostrar els preus *
if ($jornada == 0 && $userType != "Super Administrator")
{
echo "PILLIN PILLIN!!!!";
exit;
}
/*Eliminar esto quan vullgam mostrar els preus */


$userId = 1;
if ($_GET["c"] > 0)
	$userId = (int) $_GET["c"];
$infoCon = getInfoConcursant($userId);

$userLogged = conValidat();


$aEquips = getTaula("equips", "nom");
$aEq_aux = getTaula("equips", "id"); /* per mostrar nom equip correctament.. que està consultat per Id */

if($_GET["jornada"] > 0)
	$jornada = (int) $_GET["jornada"];
else
	$jornada = getJornadaActual();

if ($jornada == 0) $jornada = 1;

if($_GET["equip"] > 0) {
	$equipId = $_GET["equip"];
	$aJugadors = getPuntsJugadorsPerEquip($jornada, $equipId);
	$aTotals2 = getPuntsJugadorsTotalPerEquip($jornada, $equipId);
}
else {
	$equipId = 0;
	$aJugadors = getPuntsJugadors($jornada);
	$aTotals2 = getPuntsJugadorsTotal($jornada);
}

for ($i = 0 ; $i < sizeof($aJugadors) ; $i++)
{
	$enc = false;
	$aTotals[$i]["punts"] = "";
	for ($j = 0 ; $j < sizeof($aTotals2) && !$enc ; $j++)
	{
		if ($aJugadors[$i]["id"] == $aTotals2[$j]["id"])
		{
			$enc = true;
			$aTotals[$i]["punts"] = $aTotals2[$j]["punts"];
		}
	}
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />

<script language="javascript">
function canviaJornada(equip) {
window.location = "llistat_jugadors.php?equip=" + equip + "&jornada=" + document.getElementById("jornada").value;
}
</script>
</head>
<body>
<table border="0"  class="moduletable" align="center" width="100%" colspan="3">
<tr>
	<td width="20%"><div align="left">
		<select id="jornada" name="jornada" onChange="javascript:canviaJornada(<?= $equipId ?>)">
		<?php for ($i = 1 ; $i <= 38 ; $i++) { ?>
			<option value="<?= $i ?>" <?php if ($i == $jornada) echo "selected"; ?>><b>Jornada <?= $i ?></b></option>
		<?php } ?>
		</select>
	</td>		
	
	<td width="10%"><div align="left">
		<div class="back_button2">
		<a href="llistat_jugadors_filtro.php?">Filtrar Llistat</a>
	</div></td>
	
	<td width="70%"><div align="left">
		<div class="back_button2">
		<a href="consultajug.php">Buscar Jugador</a>
	</div></td>
</tr>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<tr>
<td width="20%" valign="top">
	<table align="left">
		<tr><td><a href="llistat_jugadors.php?jornada=<?= $jornada ?>">Llistat Complet</a></td></tr>
		<tr><td></td></tr>

<?php
	for($i = 0; $i < sizeof($aEquips); $i++) {
?>
		<tr><td><a href="llistat_jugadors.php?jornada=<?= $jornada ?>&equip=<?=$aEquips[$i]["id"] ?>"><?=utf8_encode($aEquips[$i]["nom"]); ?></a></td></tr>
<?php
	}

?>

	</table>
</td>
<td width="80%" valign="top">
	<table align="center" width="95%">
<?php
	if (sizeof($aJugadors) > 0 && $equipId > 0) {
		echo "<th align=\"center\" colspan=\"5\">" . $aEq_aux[$equipId -1]["nom"] . "</th>";
		echo "<tr align=\"center\"><td><b>Nom</b></b></td><td><b>Valor</b></td><td><b>Posició</b></td><td><b>Punts</b></td><td><b>Total</b></td></tr>";

		for($i = 0; $i < sizeof($aJugadors); $i++) {
?>
		<tr>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=utf8_encode($aJugadors[$i]["nom"]); ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=$aJugadors[$i]["valor"] ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=$POSICIONS[$aJugadors[$i]["posicio"]] ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=$aJugadors[$i]["punts"] ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=$aTotals[$i]["punts"] ?></td>
		</tr>
<?php
		}
	}
	else if (sizeof($aJugadors) > 0) {
		echo "<th align=\"center\" colspan=\"6\">Llistat Complet de Jugadors</th>";
		echo "<tr><td><b>Nom</b></td><td><b>Equip</b></td><td><b>Valor</b></td><td><b>Posició</b></td><td><b>Pts</b></td><td><b>Total</b></td></tr>";

		for($i = 0; $i < sizeof($aJugadors); $i++) {
?>
		<tr>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=utf8_encode($aJugadors[$i]["nom"]); ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=$aJugadors[$i]["sigles"] ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=$aJugadors[$i]["valor"] ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=$POSICIONS[$aJugadors[$i]["posicio"]] ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=$aJugadors[$i]["punts"] ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><i><font color="#B40404"><?php } ?><?=$aTotals[$i]["punts"] ?></td>
		</tr>
<?php
		}
	}
	else
		echo "No hi ha Jugadors disponibles.";
?>
	</table>
</td>
</tr>
</table>

</body>
</html>
