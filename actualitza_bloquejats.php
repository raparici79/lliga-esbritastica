<?php
/************************************************
*	File: 	actualitza_bloquejats.php		*
*	Desc: 	Marca els jugadors que tenen 5 participants o més *
*	Author:	Rubén Aparici 			*
************************************************/
session_start();
include("config2.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];
if (!($userId = conValidat()) || $userType == "Registered")
	header("Location: login1.php?url=actualitza_bloquejats.php");


if ((isset($_POST["i"]) && $_POST["i"] > 0) && ($userType == "Administrator" || $userType == "Super Administrator")) {
 //actualitzem 
	updateBloquejats();
	header("Location: edit_jugadors.php"); 
}

?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->

</head>
<body>

<table border="1" class="moduletable" align="center" width="300">
<tr>

<td width="100%" valign="top">
	<form name="formEdit" action="actualitza_bloquejats.php" method="post">
	<table align="center" width="95%">
<?php
	if ($userId > 0) {

	$jugadorsBlq = getJugadorsBloquejats();
	
	$i=0;
	$llistat = " \n";
	for ($j = 0; $j < sizeof($jugadorsBlq); $j++) {
		$i++;
		if ($jugadorsBlq[$j]["ecomunitari"] == 1){
			$llistat .= "  ".$i .".- ".$jugadorsBlq[$j]["nom"]." (".$jugadorsBlq[$j]["sigles"].")"."\n";   
		}
		else{
			$llistat .= "  ** ".$jugadorsBlq[$j]["nom"]." (".$jugadorsBlq[$j]["sigles"].")"."\n";   //marquem els nous a bloquejar
		}
	}
	if ($i > 0){
		$llistat .= " \n (Els ** son els nous jugadors que es marcaran) \n";
	}

	
?>
	<tr><br>Se van a bloquejar els següents jugadors:<br>
	
	<?=nl2br($llistat) ?><br>
	</tr>
		<td><input type="hidden" name="i" value="<?= $i ?>"></td>
		<tr><td colspan="3"><div align="left"><input type="submit" value="Guardar"></div></td></tr>
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