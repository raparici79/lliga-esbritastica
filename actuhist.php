<?php
/************************************************
*	File: 	actuhist.php				*
*	Desc: 	Penalitza en punts als concursants 			*
*	Author:	Rubén Aparici 						*
************************************************/

session_start();
include("config.php");
include("funcions2.php");


$userType = $_SESSION["usertype"];
if (!($userId = conValidat()) || $userType == "Registered")
	header("Location: login.php?url=actuhist.php");

	
//$aClass = getTaula("classificacions", "tipo_class");
$aClass[0] = 'Acumulada';
$aClass[1] = 'General';
$aClass[2] = 'Copa';

if (isset($_POST["c"]))
{
	$msg = guardar_classificacio($TEMP_ACTUAL,$_POST["c"]);
}

	
?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
</head>
<body>

<form name="formEdit" action="actuhist.php?" method="post">
<table border="0" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">Actualitzador Temporada <?= $TEMP_ACTUAL ?></div></th>
<tr>
	<td><div align="center">

	<select name="c">
		<option value="0">&lt;-- Selecciona Classificació --&gt;</option>
	<?php  
		for ($j = 0 ; $j < sizeof($aClass) ; $j++) {
			if ($aClass[$j] != $_POST["c"]) {
	?>
		<option value="<?= $aClass[$j] ?>"><?= $aClass[$j] ?></option>
	<?php		} else { ?>
		<option value="<?= $aClass[$j] ?>" selected><?= $aClass[$j] ?></option>
	<?php 		}
		} ?>
	</select></div>
	</td>
</tr>
<tr><td></td></tr>
<th></th>
<tr><td colspan="3"><div align="center"><input type="submit" value="Guardar"></div>
<br></br>
<center><?= $msg ?></center></td></tr>
</table>
</form>
<br><br>
<CENTER><b><font color="#ff0000">OJO! FIXA'T QUE VAS A ACTUALITZAR LA TEMPORADA <?= $TEMP_ACTUAL ?> AMB LES PUNTUACIONS DE LES CLASSIFICACIONS ACTUALS!</font></b></CENTER>
<br><br>
<div class="back_button"><a href="login.php?tancar=si">Tancar</div></a>
</body>
</html>
