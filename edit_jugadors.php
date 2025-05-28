<?php
/************************************************
*	File: 	edit_jugadors.php					*
*	Desc: 	per editar la info dels jugadors	*
*	Author:	Jose Gargallo 						*
************************************************/

session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];

if (!($userId = conValidat() || $userType == "Registered"))
	header("Location: login2.php?url=edit_jugadors.php");

if ($userType != "Administrator" && $userType != "Super Administrator")
{
echo "HAS DE SER ADMINISTRADOR PER UTILITZAR AQUESTES FUNCIONS";
exit;
}

$aEquips = getTaula("equips", "nom");
$aEq_aux = getTaula("equips", "id"); /* per mostrar nom equip correctament.. que està consultat per Id */

if($_GET["equip"] > 0)
	$equipId = $_GET["equip"];
else
	$equipId = 1;

if ($_GET["op"] == "delete" && $_GET["jId"] > 0) {
	$jugadorId = $_GET["jId"];
	deleteJugador($jugadorId);
}
if ($_GET["op"] == "dup" && $_GET["jId"] > 0) {
	$jugadorId = $_GET["jId"];
	afegixJugador($jugadorId,$equipId);
}

if ($_POST["numJugadors"] > 0) { //actualitzem els jugadors
	$numJugadors = (int) $_POST["numJugadors"];
	for ($i = 0 ; $i < $numJugadors ; $i++) {
		$jId = $_POST["j" . $i];
		$jnom = $_POST["jnom" . $i];
		$valor = $_POST["valor" . $i];
		$pos = $_POST["pos" . $i];
		$ecom = $_POST["ecom" . $i];
		
		updateJugador($jId, $jnom, $valor, $pos, $ecom);
	}
}

$aJugadors = getJugadorsPerEquip($equipId);

?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
<script language="javascript">
function borraJugador(eId,jId,nomjug) {
if (confirm("Recorda GUARDAR abans\n \n Vols eliminar a " + nomjug + "?"))
	window.location = "edit_jugadors.php?equip=" + eId + "&op=delete&jId=" + jId;
}
function afegixJugador(eId,jId,nomjug) {

if (confirm("Recorda GUARDAR abans\n \n Vols duplicar a " + nomjug + "?"))
	window.location = "edit_jugadors.php?equip=" + eId + "&op=dup&jId=" + jId;	
}
function editJugadors(numJugadors) {

	for (var i = 0; i < numJugadors; i++) {
		if (document.getElementById("valor" + i).value == "")
			document.getElementById("valor" + i).value = "0";
		
	}
	document.all.formEdit.submit();
}
</script>
</head>
<body>

<?php
echo "Per a actualitzar l'estat de bloquejat dels jugadors que tenen 5 o més persones, "; ?> <a href="actualitza_bloquejats.php">Click ací</a>  <br>
<?php echo " \n";echo " \n"; ?>
<br>
<table border="1" class="moduletable" align="center" width="100%">
<tr>
<td width="20%" valign="top">
	<table align="left">
<?php
	for($i = 0; $i < sizeof($aEquips); $i++) {
?>
		<tr><td><a href="edit_jugadors.php?equip=<?=$aEquips[$i]["id"] ?>"><?=utf8_encode($aEquips[$i]["nom"]); ?></a></td></tr>
<?php
	}
?>

	</table>
</td>
<td width="80%" valign="top">
	<form name="formEdit" action="edit_jugadors.php?equip=<?= $equipId ?>" method="post">
	<input type="hidden" name="numJugadors" value="<?= sizeof($aJugadors) ?>">
	<table align="center" width="95%">
<?php
	if (sizeof($aJugadors) > 0) {
		echo "<th align=\"center\" colspan=\"5\">" . utf8_encode($aEq_aux[$equipId - 1]["nom"]) . "</th>";
		echo "<tr align=\"center\"><td><b>Nom</b></td><td><b>Valor</b></td><td><b>Posició</b></td><td><b>Bloq.</b></td><td><b>   </b></td><td><b>   </b><td><b>   </b></td></td></tr>";

		for($i = 0; $i < sizeof($aJugadors); $i++) {
		//&nomjugstring = "a" . $aJugadors[$i]["nom"] . "a";
		$nomjugstring = '\''.utf8_encode($aJugadors[$i]["nom"]).'\'';
		
		//echo $nomjugstring;
?>
		<input type="hidden" name="j<?= $i ?>" value="<?= $aJugadors[$i]["id"] ?>">
		<tr>
			<td><input type="text" id="jnom<?= $i ?>" name="jnom<?= $i ?>" size="10" value="<?=utf8_encode($aJugadors[$i]["nom"]); ?>"></td>
			<td><input type="text" id="valor<?= $i ?>" name="valor<?= $i ?>" size="1" value="<?=$aJugadors[$i]["valor"] ?>"></td>
			<td>
			<select name="pos<?= $i ?>">
			<option value="1" <?php if ($aJugadors[$i]["posicio"] == 1) echo "selected"; ?>><?=$POSICIONS[1] ?></option>
			<option value="2" <?php if ($aJugadors[$i]["posicio"] == 2) echo "selected"; ?>><?=$POSICIONS[2] ?></option>
			<option value="3" <?php if ($aJugadors[$i]["posicio"] == 3) echo "selected"; ?>><?=$POSICIONS[3] ?></option>
			<option value="4" <?php if ($aJugadors[$i]["posicio"] == 4) echo "selected"; ?>><?=$POSICIONS[4] ?></option>									
			</select>
			</td>
			<td>
			<select name="ecom<?= $i ?>">
			<option value="0" <?php if ($aJugadors[$i]["ecomunitari"] == 0) echo "selected"; ?>><?=$ECOMUNITARI[0] ?></option>
			<option value="1" <?php if ($aJugadors[$i]["ecomunitari"] == 1) echo "selected"; ?>><?=$ECOMUNITARI[1] ?></option>								
			</select>
			</td>
			<td><a href="javascript:borraJugador(<?= $equipId ?>,<?=$aJugadors[$i]["id"] ?>,<?=$nomjugstring ?>)">[X]</a></td>
			<td><a href="javascript:afegixJugador(<?= $equipId ?>,<?=$aJugadors[$i]["id"] ?>,<?=$nomjugstring ?>)">[+]</a></td>
			<td><a href="modif_equip.php?j=<?= $aJugadors[$i]["id"] ?>"><?= $aEq_aux[$equipId - 1]["sigles"] ?></a></td>
		</tr>
<?php
		}
?>
		<tr><td colspan="5"><div align="center"><input type="button" onClick="javascript:editJugadors(<?= sizeof($aJugadors) ?>)" value="Guardar"></div></td></tr>
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
