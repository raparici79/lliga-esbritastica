<?php
/************************************************
*	File: 	elegir_jugador.php					*
*	Desc: 	anyadix jugador a la llista			*
*	Author:	Jose Gargallo 						*
************************************************/
session_start();
include("config.php");
include("funcions.php");

$userType = $_SESSION["usertype"];
$jornada = getJornadaActual();

/*Eliminar esto quan vullgam mostrar els preus *
if ($jornada == 0 && $userType != "Super Administrator")
{
echo "PILLIN PILLIN!!!!";
exit;
}
/*Eliminar esto quan vullgam mostrar els preus */

if (!($userId = conValidat()))
	header("Location: login1.php?url=fer_equip.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
<script language="javascript">

function afegixJugador(jId,cId) {

if (confirm(" Aquest jugador està bloqujat!!!\n \n Segur que vols continuar???"))
	window.location = "fer_equip.php?op=addJugador&id=" + jId + "&c=" + cId;

}
</script>
</head>
<body>
<?php
$jornadaActual = getJornadaActual();
if ($jornadaActual == $JORNADA_BASE  || $userType == "Administrator" || $userType == "Super Administrator") { //sols si es la primera jornada (En decembre es canvia aquest valor)

if (isset($_GET["pos"]) && $_GET["pos"] >= 1 && $_GET["pos"] <= 4) { //posicio correcta
?>
<table align="center" border="1" class="moduletable">
<?php
	$pos = $_GET["pos"];
	$aJugadors = getJugadorsPerPosicio($pos);
?>
	<th align="center" colspan="4"><?= $POSICIONS[$pos] ?></th>
	<tr><td><div align="center"><b>Nom</b></div></td><td><div align="center"><b>Equip</b></div></td><td><div align="center"><b>Valor</b></div></td><td><div align="center"><b>Bloquejat</b></div></td></tr>
<?php
	for($i = 0; $i < sizeof($aJugadors); $i++) {
?>
		<tr>
		<td>
			<?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?>
			<a href="javascript:afegixJugador(<?=$aJugadors[$i]["id"] ?>,<?=$_GET["c"] ?>)"><?=utf8_encode($aJugadors[$i]["nom"]); ?></a><b> &#9733;</b>
			<?php } else { ?>
			<a href="fer_equip.php?op=addJugador&id=<?= $aJugadors[$i]["id"] ?>&c=<?= $_GET["c"] ?>"><?=utf8_encode($aJugadors[$i]["nom"]); ?></a>
			<?php } ?>
		</td>
		<td><?=$aJugadors[$i]["sigles"] ?></td>
		<td><?=$aJugadors[$i]["valor"] ?></td>
		<td align="center"><?=$ECOMUNITARI[$aJugadors[$i]["ecomunitari"]] ?></td>
		</tr>
<?php		
	}
}
?>
</table>

<?php } else {//fi si es la jornada 0?>
No tens acces a aquesta pàgina
<?php } ?>
<br>
<div class="back_button"><a href="login1.php?tancar=si">Tancar</a></div>
</body>
</html>
