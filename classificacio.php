<?php
/************************************************
*	File: 	classificacio.php					*
*	Desc: 	class jornada i la general.			*
*	Author:	Jose Gargallo 						*
************************************************/
 
include("config.php");
include("funcions2.php");

if($_GET["jornada"] > 0)
	$jornada = (int) $_GET["jornada"];
else
	$jornada = getJornadaActual();

$class = getPuntsPerJornada($jornada);
$classTotal = getPuntsTotals($jornada);
$classGen = getPuntsGeneral($jornada);

/* anira en la jornada 0? */
$classTotalAnt = getPuntsTotals($jornada-1);
$classGenAnt = getPuntsGeneral($jornada-1);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
<script language="javascript">
function canviaJornada() {
window.location = "classificacio.php?jornada=" + document.getElementById("jornada").value;
}
function openEnfrontament(id1, id2, j) {
window.open("enfront.php?id1=" + id1 + "&id2=" + id2 + "&j=" + j, "enfrontament", 'left=250,top=250,width=650,height=500,toolbar=0');
}
</script>
<style>
select { color:black; background-color:white;}
</style>
</head>
<body>
<!--
<table align="center" width="100%">
<tr>
<td><div align="left">Per a consultar la històrica i anys anteriors, <u><a href="historica.php">click ací</a></u>.</div></td>
</tr>
</table>
-->


<table border="0" class="moduletable" align="center" width="100%">
<tr>
	<td><div align="left">
		<select id="jornada" name="jornada" onChange="javascript:canviaJornada()">
		<?php for ($i = 1 ; $i <= 38 ; $i++) { ?>
			<option value="<?= $i ?>" <?php if ($i == $jornada) echo "selected"; ?>><B>JORNADA</B> <?= $i ?></option>
		<?php } ?>
		</select>
		</div>
	</td>
</tr>
</table>

<?php

 if (sizeof($class) > 0) {
?>
	<table border="1" class="moduletable" align="center" width="100%">
	<th colspan="5"><div align="center">Classificació de la jornada</div></th>
	<tr><td width="5%"><b>&#9813;</b></td><td width="30%"><b>Mànager</b></td><td width="55%"><b>Equip</b></td><td width="10%"><b>Punts</b></td></tr>
	<?php
		for($i = 0; $i < sizeof($class); $i++) {
	?>
			<tr><td><?= $i+1 ?></td><td><a href="punts_jugadors.php?c=<?=$class[$i]["id"] ?>&jo=<?= $jornada ?>"><?=utf8_encode($class[$i]["nom"]); ?></a></td><td><?= utf8_encode($class[$i]["nom_equip"]); ?></td><td><?= $class[$i]["punts"] ?></td></tr>
	<?php
		}
	?>
	</table>
<?php
}
else {
	if (foraLimit()) {
		?>	
		<font color="#009933" face="Arial"><b>Ha començat la jornada! Esperant les primeres piques!</b></font>
		<br>	
		<?php }
		else {
		?>	
		<font color="#990000" face="Arial"><b>Jornada Activada! Data límit per fer l'equip: <?= getValorConfiguracio("data_limit") ?></b></font>
		<br>	
		<?php }
	}
	?>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="5"><div align="center">Classificació Acumulada</div></th>
<tr><td width="2%"><b> </b></td><td width="3%"><b>&#9813;</b></td><td width="30%"><b>Mànager</b></td><td width="55%"><b>Equip</b></td><td width="10%"><b>Punts</b></td></tr>
<?php
/* CHAPUZA PER A QUE AGUANTEN ELS SIMBOLETS DE MAYOR/MENOR FINS QUE PUNTUEM A ALGUN JUGADOR */
	if (sizeof($class) == 0 && $jornada > 2) {
		$classTotalAnt = getPuntsTotals($jornada-2);
		$classGenAnt = getPuntsGeneral($jornada-2);
	}
	for($i = 0; $i < sizeof($classTotal); $i++) {
		/* Posicio jornada anterior per a marcar el canvi de posició */
		if ($jornada > 1){
			$j=0;
			while ($classTotalAnt[$j]["nom"] != $classTotal[$i]["nom"])		$j++;
		}
?>
		<tr>
		<?php if ($i < $j){ ?><td><div align="center"><img src='images/mayor1.png' border='0'></div></td> 
		<?php }else if ($i > $j){ ?><td><div align="center"><img src='images/menor1.png' border='0'></div></td>  
		<?php }else{ ?><td><div align="center"><img src='images/igual1.png' border='0'></div></td>
		<?php }?>
		
		<td><?= $i+1 ?></td>
		<td><?= utf8_encode($classTotal[$i]["nom"]); ?></td>
		<td><?= utf8_encode($classTotal[$i]["nom_equip"]); ?></td>
		<td><?= $classTotal[$i]["punts"] ?></td>
		</tr>
<?php
	}
?>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="5"><div align="center">Enfrontaments de la Jornada <?= $jornada ?></div></th>
<tr><td width="35%"><b><div align="right">Equip</div></b></td><td width=""><div align="center"><b>Punts</b></div></td><td width=""><div align="center"><b></b></div></td><td width=""><b><div align="center">Punts</b></div></td><td width="35%"><b>Equip</b></td></tr>
<?php
	$enfront = getEnfrontaments($jornada);
	for($i = 0; $i < sizeof($enfront); $i++) {
?>
		<tr><td><div align="right"><?=utf8_encode($enfront[$i][0]["nom"]); ?></div></td><td><div align="center"><?=$enfront[$i][0]["punts"] ?></div></td>
		<td>
		<?php if (foraLimitRivals() || $jornada < getJornadaActual()) { ?>		
		<div align="center"><a href="javascript:openEnfrontament(<?=$enfront[$i][0]["id"] ?>,<?=$enfront[$i][1]["id"] ?>,<?=$jornada ?>)"><div align="center"><img src='images/ojoo1.png' alt="Image" style="width:15px;height:11px;" border='0'></div></a></div></td>
		<?php } ?>
		</td>
		<td><div align="center"><?=$enfront[$i][1]["punts"] ?></div></td>
		<td><?=utf8_encode($enfront[$i][1]["nom"]); ?></td></tr>
<?php
	}
?>
</table>


<table border="1" class="moduletable" align="center" width="100%">
<th colspan="6"><div align="center">Classificació General</div></th>
<tr><td width="2%"><b> </b></td><td width="3%"><b>&#9813;</b></td><td width="30%"><b>Mànager</b></td><td width="55%"><b>Equip</b></td><td width="5%"><b>Punts</b></td><td width="5%"><div align="center"><font size="1"><b>&#9917 </b></font></div></td></tr>
<?php
	for($i = 0; $i < sizeof($classGen); $i++) {
			if ($jornada > 1){
			$j=0;
			while ($classGenAnt[$j]["nom"] != $classGen[$i]["nom"])		$j++;
		}
?>
		<tr>
		<?php if ($i < $j){ ?><td><div align="center"><img src='images/mayor1.png' border='0'></div></td> 
		<?php }else if ($i > $j){ ?><td><div align="center"><img src='images/menor1.png' border='0'></div></td>  
		<?php }else{ ?><td><div align="center"><img src='images/igual1.png' border='0'></div></td>
		<?php }?>
		<td><?= $i+1 ?></td><td><?=utf8_encode($classGen[$i]["nom"]); ?></td><td><?= utf8_encode($classGen[$i]["nom_equip"]); ?></td><td><?= $classGen[$i]["punts"] ?></td>
		<td><?php if ($classGen[$i]["puntaverage"] > 0) echo "+"; ?><?= $classGen[$i]["puntaverage"] ?></td>
		</tr>
<?php
	}
?>
</table>
</body>
</html>
