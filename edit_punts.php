<?php
/************************************************
*	File: 	edit_punts.php						*
*	Desc: 	per a ficar els punts per jornada	*
*	Author:	Jose Gargallo 						*
************************************************/

session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];

if (!($userId = conValidat() || $userType == "Registered"))
	header("Location: login2.php?url=edit_punts.php");

if ($userType != "Administrator" && $userType != "Super Administrator")
{
echo "HAS DE SER ADMINISTRADOR PER UTILITZAR AQUESTES FUNCIONS";
exit;
}

if($_GET["jornada"] > 0)
	$jornada = (int) $_GET["jornada"];
else
	$jornada = getJornadaActual();

$aEquips = getTaula("equips", "nom");
$aEq_aux = getTaula("equips", "id"); /* per mostrar nom equip correctament.. que està consultat per Id */

if($_GET["equip"] > 0)
	$equipId = $_GET["equip"];
else
	$equipId = 1;

if ($_POST["numJugadors"] > 0) { //actualitzem els jugadors
	$numJugadors = (int) $_POST["numJugadors"];
	for ($i = 0 ; $i < $numJugadors ; $i++) {
		$jId = $_POST["j" . $i];
		$punts = $_POST["punts" . $i];
		if ($punts != "")
			updatePuntsJugador($jId, $jornada, $punts);
	}
}

$aJugadors = getPuntsJugadorsPerEquip($jornada, $equipId);
$jornadaActual = getJornadaActual() + 1;
$aviscopa = EsJornadaCopa($jornadaActual);
?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
<script language="javascript">
function canviaJornada(equip) {
window.location = "edit_punts.php?jornada=" + document.getElementById("jornada").value + "&equip=" + equip;
}
function editPunts(numJugadors) {
/*
	for (var i = 0; i < numJugadors; i++) {
		if (document.getElementById("punts" + i).value == "")
			document.getElementById("punts" + i).value = "0";

	}
*/
	document.all.formEdit.submit();
}
</script>
</head>
<body>
<br><P><?php if ($aviscopa > 0)  echo "<font size=\"4\" color=\"#ff0000\">La proxima jornada hay COPA.. Avisar a la gente y hacer sorteo</font>"; ?></P>
<table border="1" class="moduletable" align="center" width="100%">
<th>Jornada <?= $jornada ?></th>
<tr>
	<td><div align="center">
		Canviar a:
		<select id="jornada" name="jornada" onChange="javascript:canviaJornada(<?= $equipId ?>)">
		<?php for ($i = 1 ; $i <= 38 ; $i++) { ?>
			<option value="<?= $i ?>" <?php if ($i == $jornada) echo "selected"; ?>>Jornada <?= $i ?></option>
		<?php } ?>
		</select>
		</div>
	</td>
</tr>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<tr>
<td width="20%" valign="top">
	<table align="left">
<?php
	for($i = 0; $i < sizeof($aEquips); $i++) {
?>
		<tr><td><a href="edit_punts.php?equip=<?=$aEquips[$i]["id"] ?>&jornada=<?= $jornada ?>"><?=utf8_encode($aEquips[$i]["nom"]); ?></a></td></tr>
<?php
	}
?>

	</table>
</td>
<td width="80%" valign="top">
	<form name="formEdit" action="edit_punts.php?equip=<?= $equipId ?>&jornada=<?= $jornada ?>" method="post">
	<input type="hidden" name="numJugadors" value="<?= sizeof($aJugadors) ?>">
	<table align="center" width="95%">
<?php
	if (sizeof($aJugadors) > 0) {
		echo "<th align=\"center\" colspan=\"5\">" . utf8_encode($aEq_aux[$equipId -1]["nom"]) . "</th>";
		echo "<tr align=\"left\"><td><b>Punts</b></td><td><b>Nom</b></b></td><td><b>Valor</b></td><td><b>Posició</b></td></tr>";

		for($i = 0; $i < sizeof($aJugadors); $i++) {
		
		if ($i%2==0){
?>
		<input type="hidden" name="j<?= $i ?>" value="<?= $aJugadors[$i]["id"] ?>">
		<tr bgcolor = "#87CEEB"> 
		
			<td><input type="text" id="punts<?= $i ?>" name="punts<?= $i ?>" value="<?=$aJugadors[$i]["punts"] ?>" size="3" maxlength="3"></td>
			<td><?=utf8_encode($aJugadors[$i]["nom"]); ?></td>
			<td><?=$aJugadors[$i]["valor"] ?></td>
			<td><?=$POSICIONS[$aJugadors[$i]["posicio"]] ?></td>			
		</tr>
<?php
		}
		else {
?>
		<input type="hidden" name="j<?= $i ?>" value="<?= $aJugadors[$i]["id"] ?>">
		<tr bgcolor = "#ADD8E6"> 
		
			<td><input type="text" id="punts<?= $i ?>" name="punts<?= $i ?>" value="<?=$aJugadors[$i]["punts"] ?>" size="3" maxlength="3"></td>
			<td><?=utf8_encode($aJugadors[$i]["nom"]); ?></td>
			<td><?=$aJugadors[$i]["valor"] ?></td>
			<td><?=$POSICIONS[$aJugadors[$i]["posicio"]] ?></td>
		</tr>
<?php
		}
		}
		
?>
		<tr><td colspan="5"><div align="center"><input type="submit" value="Guardar"></div></td></tr>
<?php
	}
	else
		echo "No hi ha Jugadors disponibles.";
?>
	</table>
	</form>
</td>
</tr>
</table>
<br><br>
<div class="back_button"><a href="login2.php?tancar=si">Tancar</a></div>
</body>
</html>
