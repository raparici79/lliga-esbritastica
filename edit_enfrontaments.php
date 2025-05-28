<?php
/************************************************
*	File: 	edit_enfrontaments.php				*
*	Desc: 	edita els enfrontaments 			*
*	Author:	Jose Gargallo 						*
************************************************/

session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];

if (!($userId = conValidat() || $userType == "Registered"))
	header("Location: login2.php?url=edit_enfrontaments.php");

if ($userType != "Administrator" && $userType != "Super Administrator")
{
echo "HAS DE SER ADMINISTRADOR PER UTILITZAR AQUESTES FUNCIONS";
exit;
}

if($_GET["jornada"] > 0)
	$jornada = (int) $_GET["jornada"];
else
	$jornada = getJornadaActual();
	
if ($jornada == 0)	$jornada = 1;

$aCon = getTaula("concursants", "usuari");



if ($_POST["e0"] > 0) { //actualitzem els enfrontaments
	$num = sizeof($aCon);

	for ($i = 0 ; $i < $num ; $i+=2) {
		$cId1 = $_POST["e" . $i];
		$cId2 = $_POST["e" . ($i+1)];
		if ($cId1 > 0 && $cId2 > 0)
			addEnfrontament($cId1, $cId2, $jornada);		
	}
}

$jornadaEditada = jornadaAmbEnfrontaments($jornada);

$aCon = getTaula("concursants", "usuari");

?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
<script language="javascript">
function canviaJornada() {
window.location = "edit_enfrontaments.php?jornada=" + document.getElementById("jornada").value;
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

<table border="1" class="moduletable" align="center" width="100%">
<th>Jornada <?= $jornada ?></th>
<tr>
	<td><div align="center">
		Canviar a: 
		<select id="jornada" name="jornada" onChange="javascript:canviaJornada()">
		<?php for ($i = 1 ; $i <= 38 ; $i++) { ?>
			<option value="<?= $i ?>" <?php if ($i == $jornada) echo "selected"; ?>>Jornada <?= $i ?></option>
		<?php } ?>
		</select>
		</div>
	</td>
</tr>
</table>
<?php 
if ($jornadaEditada == 0) {
?>
<form name="formEdit" action="edit_enfrontaments.php?jornada=<?= $jornada ?>" method="post">
<table border="0" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">enfrontaments</div></th>
<?php
	
	for ($i = 0 ; $i < sizeof($aCon) ; $i += 2) {
 ?>
<tr>
	<td><div align="center">

	<select name="e<?= $i ?>">
		<option value="0">&lt;-- Selecciona Concursant --&gt;</option>
	<?php  
		for ($j = 0 ; $j < sizeof($aCon) ; $j++) {
	?>
		<option value="<?= $aCon[$j]["id"] ?>"><?= $aCon[$j]["usuari"] ?></option>
	<?php } ?>
	</select></div>
	</td>
	<td>Vs.</td>
	<td><div align="center">
	<select name="e<?php echo ($i+1); ?>">
		<option value="0">&lt;-- Selecciona Concursant --&gt;</option>
	<?php  
		for ($j = 0 ; $j < sizeof($aCon) ; $j++) {
	?>
		<option value="<?= $aCon[$j]["id"] ?>"><?= $aCon[$j]["usuari"] ?></option>
	<?php } ?>
	</select></div>
	</td>
</tr>
<tr><td></td></tr>
<?php
	}
?>
<tr><td colspan="3"><div align="center"><input type="submit" value="Guardar"></div></td></tr>
</table>
</form>
<font color="#FF0000">ATENCIÓ!!!Una volta guardada la jornada NO pordrás modificar-la, assegurat q tot està correcte abans de guardar!</font>
<?php } ?>
<br><br>
<div class="back_button"><a href="login2.php?tancar=si">Tancar</a></div>
</body>
</html>
