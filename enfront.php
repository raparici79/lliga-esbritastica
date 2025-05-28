<?php
/************************************************
*	File: 	enfront_copa.php					*
*	Desc: 	dibuixa un enfrontament	de copa		*
*	Author:	Rubén Aparici 						*
************************************************/

include("config.php");
include("funcions.php");


$c1 = getInfoConcursant($_GET["id1"]);
$c2 = getInfoConcursant($_GET["id2"]);
$jornada = $_GET["j"];
$aliniacio1 = getAliniacio($c1["id"], $jornada);
$aliniacio2 = getAliniacio($c2["id"], $jornada);
$aJugadors1 = getPuntsJugadorsPerConcursant($c1["id"], $jornada);
$aJugadors2 = getPuntsJugadorsPerConcursant($c2["id"], $jornada);

$total1 = 0; $p1 = 0; $de1 = 0; $m1 = 0; $da1 = 0;
for ($i = 0; $i < sizeof($aliniacio1); $i++) {
	$enc = false;
	$aliniacio1[$i]["punts"] = 0;
	switch ($aliniacio1[$i]["posicio"]) {
	case 1: $p1++; break;
	case 2: $de1++; break;
	case 3: $m1++; break;
	case 4: $da1++; break;
	}
	for ($j = 0; $j < sizeof($aJugadors1) && !$enc; $j++) {
		if ($aliniacio1[$i]["id"] == $aJugadors1[$j]["id"]) {
			$enc = true;
			$aliniacio1[$i]["punts"] = $aJugadors1[$j]["punts"];
		}
	}
	$total1 += $aliniacio1[$i]["punts"];
}


$total2 = 0; $p2 = 0; $de2 = 0; $m2 = 0; $da2 = 0;
for ($i = 0; $i < sizeof($aliniacio2); $i++) {
	$enc = false;
	$aliniacio2[$i]["punts"] = 0;
	switch ($aliniacio2[$i]["posicio"]) {
	case 1: $p2++; break;
	case 2: $de2++; break;
	case 3: $m2++; break;
	case 4: $da2++; break;
	}
	for ($j = 0; $j < sizeof($aJugadors2) && !$enc; $j++) {
		if ($aliniacio2[$i]["id"] == $aJugadors2[$j]["id"]) {
			$enc = true;
			$aliniacio2[$i]["punts"] = $aJugadors2[$j]["punts"];
		}
	}
	$total2 += $aliniacio2[$i]["punts"];
}

?>
<html>
<head>
<style>
body {
	margin: 0px;
	height: 100%;
	padding: 0px;
	font-family: Sans Serif, Arial, Helvetica;
	font-size: 12x;
	font-weight:bold;
	color: #DFDFDF;
	
			background-image:url(images/fondo_copa6.jpg);
	
	/* Fijar la imagen de fondo este vertical y
    horizontalmente y centrado */
  background-position: center center;

  /* Esta imagen no debe de repetirse */
  background-repeat: no-repeat;

  /* COn esta regla fijamos la imagen en la pantalla. */
  background-attachment: fixed;

  /* La imagen ocupa el 100% y se reescala */
  background-size: 98% 94%;

  /* Damos un color de fondo mientras la imagen está cargando  */
  background-color: #000000;

}



table.moduletable {
width: 98%;
height: 92%;
valign: middle;
	font-size:24px;
	font-weight:bold;
	padding: 0px;
	border-spacing: 0px;
	border-collapse: collapse;
	text-align: center;
}

table.moduletableaux { /* entrenador + total */
width: 98%;
height: 3%;
	font-size:16px;
	font-weight:bold;
	padding: 0px;
	border-spacing: 0px;
	border-collapse: collapse;
	text-align: center;
	background: #000000;
}

table.moduletablefila {
width: 98%;
height: 11%;  /* 8 filas por 10% = 80% */
	font-size:24px;
	font-weight:bold;
valign: middle;
  background-color: transparent;

	text-align: center;
		
}

table.moduletablesep { /* separación medio campo */
width: 98%;
height: 5%;
  background-color: transparent;

	text-align: center;
		
}


</style>

<!-- 
	tr-> fila: Para alinearlo verticalmente utilizaremos el atributo “valign” 
	para poder alinearlo arriba de la celda (“top”), en el centro (“middle”) o debajo (“bottom”).
    
	td-> celda: Al igual que en las filas, en las celdas podemos definir el la alineación del 
	contenido que está dentro con los atributos “valign” y “align”
	Las celdas poseen unos atributos que nos ayudan a poder agrupar tantas celdas o tantas columnas como indiquemos en él. 
	Para agrupar celdas utilizaríamos el atributo “colspan” y para agrupar celdas el atributo “rowspan”..

	th->Las celdas escritas con la etiqueta th> y su correspondiente cierre, admiten los mismos atributos que las etiquetas 
	td> y funcionan de la misma forma, salvo que el contenido que esté dentro de una etiqueta th> 
	está considerado como el encabezado de la tabla, por lo que se creará en negrita y centrado sin que nosotros se lo indiquemos.
-->
</head>


<body bgcolor="#000000">

<table class="moduletableaux" align="center">
<th><div align="center"><font color="#CC6600" size="6"><?= utf8_encode(strtoupper($c1["nom"]))?> (<?="$total1"?>)</font></div></th>
</table>


	<!--porter -->
	<table class="moduletablefila" align="center" style="text-align:center;">
	<tr><td><div align="center"><img src='camisetes/<?=$aliniacio1[0]["sigles"]?>.png' alt="" width="70" height="60" border='0'><br><?= utf8_encode($aliniacio1[0]["nom"]); ?> <br>(<?= $aliniacio1[0]["sigles"] ?> <?= $aliniacio1[0]["punts"] ?>)</div></td></tr>
	</table>

	<!--defenses-->
	<table class="moduletablefila" align="center" style="text-align:center;">
	<tr>
	<?php
	for ($i = 1; $i <= $de1; $i++) {

			if ($i==1) $alineado="center";
			else if ($i==$de1) $alineado="center";
				else $alineado="center";

	?>
	<td width="<?=100/$de1?>%"><div align="<?= $alineado ?>"><img src='camisetes/<?=$aliniacio1[$i]["sigles"]?>.png' width="70" height="60" border='0'><br><?= utf8_encode($aliniacio1[$i]["nom"]); ?> <br>(<?= $aliniacio1[$i]["sigles"] ?> <?= $aliniacio1[$i]["punts"] ?>)</div></td>
	<?php } ?>
	</tr>
	</table>

	<!--mitjos-->
	<table class="moduletablefila" align="center" style="text-align:center;">
	<tr>
	<?php
	for ($i = $de1 + 1; $i <= $de1 + $m1; $i++) {
				if ($i==$de1+1) $alineado="center";
			else if ($i==$de1+$m1) $alineado="center";
				else $alineado="center";
	?>
	<td width="<?=100/$m1?>%"><div align="<?= $alineado ?>"><img src='camisetes/<?=$aliniacio1[$i]["sigles"]?>.png' alt="" width="70" height="60" border='0'><br><?= utf8_encode($aliniacio1[$i]["nom"]); ?><br>(<?= $aliniacio1[$i]["sigles"] ?> <?= $aliniacio1[$i]["punts"] ?>)</div></td>
	<?php } ?>
	</tr>
	</table>

	<!--davanters-->
	<table class="moduletablefila" align="center" style="text-align:center;">
	<tr>
	<?php
	for ($i = $de1 + $m1 + 1; $i <= $de1 + $m1 + $da1; $i++) {
	?>
	<td width="<?=100/$da1?>%"><div align="center"><img src='camisetes/<?=$aliniacio1[$i]["sigles"]?>.png' width="70" height="60" border='0'><br><?= utf8_encode($aliniacio1[$i]["nom"]); ?> <br>(<?= $aliniacio1[$i]["sigles"] ?> <?= $aliniacio1[$i]["punts"] ?>)</div></td>
	<?php } ?>
	</tr>
	</table>



	<table class="moduletablesep" align="center" style="text-align:center;">
	<tr>
	</table>


	<!--davanters -->
	<table class="moduletablefila" align="center" style="text-align:center;">
	<tr>
	<?php
	for ($i = $de2 + $m2 + 1; $i <= $de2 + $m2 + $da2; $i++) {
	?>
	<td width="<?=100/$da2?>%"><div align="center"><img src='camisetes/<?=$aliniacio2[$i]["sigles"]?>.png' width="70" height="60" border='0'><br><?= utf8_encode($aliniacio2[$i]["nom"]); ?> <br>(<?= $aliniacio2[$i]["sigles"] ?> <?= $aliniacio2[$i]["punts"] ?>)</div></td>
	<?php } ?>
	</tr>
	</table>

	<!--mitjos -->
	<table class="moduletablefila" align="center" style="text-align:center;">
	<tr>
	<?php
	for ($i = $de2 + 1; $i <= $de2 + $m2; $i++) {
	?>
	<td width="<?=100/$m2?>%"><div align="center"><img src='camisetes/<?=$aliniacio2[$i]["sigles"]?>.png' width="70" height="60" border='0'><br><?= utf8_encode($aliniacio2[$i]["nom"]); ?> <br>(<?= $aliniacio2[$i]["sigles"] ?> <?= $aliniacio2[$i]["punts"] ?>)</div></td>
	<?php } ?>
	</tr>
	</table>

	<!--defenses -->
	<table class="moduletablefila" align="center" style="text-align:center;">
	<tr>
	<?php
	for ($i = 1; $i <= $de2; $i++) {
	?>
	<td width="<?=100/$de2?>%"><img src='camisetes/<?=$aliniacio2[$i]["sigles"]?>.png' width="70" height="60" border='0'><br><?= utf8_encode($aliniacio2[$i]["nom"]); ?> <br>(<?= $aliniacio2[$i]["sigles"] ?> <?= $aliniacio2[$i]["punts"] ?>)</td>
	<?php } ?>
	</tr>
	</table>

	<!--porter -->
	<table class="moduletablefila" align="center" style="text-align:center;">
	<tr><td><div align="center"><img src='camisetes/<?=$aliniacio2[0]["sigles"]?>.png' width="70" height="60" border='0'><br><?= utf8_encode($aliniacio2[0]["nom"]); ?> <br>(<?= $aliniacio2[0]["sigles"] ?> <?= $aliniacio2[0]["punts"] ?>)</div></td></tr>
	</table>


<table class="moduletableaux" align="center">
<th><div align="center"><font color="#CC6600" size="6"><?= utf8_encode(strtoupper($c2["nom"]))?> (<?="$total2"?>)</font></div></th>


</body>
</html>