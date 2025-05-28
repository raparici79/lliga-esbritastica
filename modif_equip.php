<?php
/************************************************
*	File: 	modif_equip.php			*
*	Desc: 	modifica equip de jugador	*
*	Author:	Rubén Aparici 			*
************************************************/
session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];
if (!($userId = conValidat()) || $userType == "Registered")
	header("Location: login1.php?url=modif_equip.php");

$idjugador = $_GET["j"];
$jugador = getInfoJugador($idjugador);
$aEquips = getTaula("equips", "id");


if ($_POST["e"] > 0) { //actualitzem 
//	$jId = (int) 
	$jId = $_POST["j"];
	$eq = $_POST["e"];
		
	updateEquipJugador($jId, $eq);

	header("Location: edit_jugadors.php");

}

?>

<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->

</head>
<body>

<table border="1" class="moduletable" align="center" width="100%">
<tr>
<td width="20%" valign="top">
	<table align="left">

	</table>
</td>
<td width="80%" valign="top">
	<form name="formEdit" action="modif_equip.php?j=<?= $idjugador ?>" method="post">
	<table align="center" width="95%">
<?php
	if ($idjugador > 0) {
		$pos = $jugador["equip"] - 1;
		echo "<th align=\"center\" colspan=\"5\">" . $jugador["nom"] . "</th>";
		echo "<tr><td>Actual: " . $aEquips[$pos]["nom"]  . "</td></tr>";
?>
		<input type="hidden" name="j" value="<?= $idjugador ?>">
		<tr>
			<td>
			<select name="e">
			<?php for($i = 0; $i < sizeof($aEquips); $i++) {?>
			<option value="<?= $aEquips[$i]["id"] ?>" <?php if ($aEquips[$i]["id"]  == $jugador["equip"]) echo "selected"; ?>>Fitxa pel:  <?= $aEquips[$i]["nom"] ?></option>

			<?php } ?>
			</select>
			</td>
		</tr>

		<tr><td colspan="3"><div align="center"><input type="submit" value="Guardar"></div></td></tr>
<?php
	}
	else	{
		?>
		<tr><td><a href="edit_jugadors.php ?>">Tornar</a></td></tr>
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