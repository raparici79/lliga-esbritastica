<?php
/************************************************
*	File: 	penalitzar.php				*
*	Desc: 	Penalitza en punts als concursants 			*
*	Author:	Jose Gargallo 						*
************************************************/

session_start();
include("config.php");
include("funcions.php");

$userType = $_SESSION["usertype"];

if (!($userId = conValidat() || $userType == "Registered"))
	header("Location: login2.php?url=penalitzar.php");

if ($userType != "Administrator" && $userType != "Super Administrator")
{
echo "HAS DE SER ADMINISTRADOR PER UTILITZAR AQUESTES FUNCIONS";
exit;
}

$aCon = getTaula("concursants", "usuari");

if (isset($_POST["c"]) && $_POST["c"] > 0 && $_POST["p"] != 0)
{
	$msg = penalitzar($_POST["c"], $_POST["p"], $_POST["d"]);
}

?>
<html>
<head>
<link href="estil.css" rel="stylesheet" type="text/css"/>
</head>
<body>

<form name="formEdit" action="penalitzar.php?" method="post">
<table border="0" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">Penlitzacions</div></th>
<tr>
	<td><div align="center">

	<select name="c">
		<option value="0">&lt;-- Selecciona Concursant --&gt;</option>
	<?php  
		for ($j = 0 ; $j < sizeof($aCon) ; $j++) {
			if ($aCon[$j]["id"] != $_POST["c"]) {
	?>
		<option value="<?= $aCon[$j]["id"] ?>"><?= utf8_encode($aCon[$j]["usuari"]); ?></option>
	<?php		} else { ?>
		<option value="<?= $aCon[$j]["id"] ?>" selected><?= utf8_encode($aCon[$j]["usuari"]); ?></option>
	<?php 		}
		} ?>
	</select>&nbsp;<input type="text" value="0" name="p" size="4"></div>
	</td>
</tr>
<br></br>
<tr>
	<td><div align="center">
<textarea rows="3" name="d" cols="29">Apuntar jornada i explicacio si fa falta</textarea> 
	</td>
</tr>


<tr><td></td></tr>
<tr><td colspan="3"><div align="center"><input type="submit" value="Guardar"></div>
<br></br>
<center><?= $msg ?></center></td></tr>
</table>
</form>
<font color="#ff0000">Els punts que fiques se li restaran al total, per lo tant si vos penalitzar en 3 punts ficaras 3 i no -3. Al guardar voràs el total del concursant elegit</font>
<br><br>
<div class="back_button"><a href="login2.php?tancar=si">Tancar</a></div>
</body>
</html>
