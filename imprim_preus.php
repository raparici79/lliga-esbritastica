<?php
/************************************************
*	File: 	imprim_preus.php					*
*	Desc: 	imprimir preus          			*
*	Author:	Rubén Aparici 						*
************************************************/
session_start();
include("config.php");
include("funcions.php");

$userType = $_SESSION["usertype"];

$jornadaActual = getJornadaActual();
$jornada = getJornadaActual();

$userLogged = conValidat();

if (/*$userLogged != 7 or*/ $userLogged == 0)
{
echo "NO ESTAS CONNECTAT." ;
echo "<br>" ;
echo "<br>" ;
echo "TANCA AQUESTA FINESTRA.";
exit;
}

// if ($jornada == 0 and $userLogged != 7)
// {
// echo "PILLIN PILLIN!!!!";
// exit;
// }
$aEquips = getTaula("equips", "sigles");
?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
</head>
<body>
<?php
$jornadaActual = getJornadaActual();
if ($jornadaActual == $JORNADA_BASE  || $userType == "Administrator" || $userType == "Super Administrator") { //sols si es la primera jornada (En decembre es canvia aquest valor)
?>
<!--<a href="imprimix_preus.php">Imprimir</a><b>&nbsp;/&nbsp;</b><a href="descarrega_preus.php">Descarregar</a> -->

<?php	//echo $aEquips[0][0];
	for ($j = 0 ; $j < 20; $j++) {
	$aJugadors = getJugadorsPerEquipImpr($aEquips[$j][0]);
?><br><br><table border="1" class="moduletable" align="center" width="100%">

	<th align="center" colspan="4"><?= utf8_encode($aJugadors[$j]["nomeq"]) ?></th>
	<tr><td width="40%"><div align="left"><b>Nom</b></div></td><td width="15%"><div align="left"><b>Equip</b></div></td><td width="25%"><div align="left"><b>Posicio</b></div></td>
	<td width="20%"><div align="left"><b>Valor</b></div></td>
<?php
	for($i = 0; $i < sizeof($aJugadors); $i++) {
?>
	<tr>		
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><strike><font color="#B40404"><?php } ?><?=utf8_encode($aJugadors[$i]["nom"]) ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><strike><font color="#B40404"><?php } ?><?=$aJugadors[$i]["sigles"] ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><strike><font color="#B40404"><?php } ?><?=$POSICIONS[$aJugadors[$i]["posicio"]] ?></td>
			<td><?php if ($aJugadors[$i]["ecomunitari"] == 1) { ?><strike><font color="#B40404"><?php } ?><?=$aJugadors[$i]["valor"] ?></td>

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
</body>
</html>
