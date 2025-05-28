<?php
/************************************************
*	File: 	llistat_jugadors.php				*
*	Desc: 	Llista els equips i jugadors amb filtro	*
*	Author:	Rubén Aparici						*
************************************************/
session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];
$jornada = getJornadaActual();

/*Eliminar esto quan vullgam mostrar els preus *
if ($jornada == 0 && $userType != "Super Administrator")
{
echo "PILLIN PILLIN!!!!";
exit;
}
/*Eliminar esto quan vullgam mostrar els preus */

$aEq = getTaula("equips", "nom");
$aPreu = getPreus();
	
if (isset($_POST["e"]))
	$consulta1 = getJugadorsFiltrats($_POST["e"], $_POST["pr"], $_POST["pos"]);// també obtenim el id del jugador. 
else
	$consulta1 = getJugadorsFiltrats(99, 99, 99);

?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />

</head>
<body>
<form name="formEdit" action="llistat_jugadors_filtro.php?" method="post">
<table border="0" class="moduletable" align="center" width="100%">
<div align="center"> </div>
<tr>
	<td><div align="center">

<!-- EQUIP INI -->
	<select name="e">
		<option value="99">Equip</option>
	<?php  
		for ($j = 0 ; $j < sizeof($aEq) ; $j++) {
			if ($aEq[$j]["id"] != $_POST["e"]) {?>			
				<option value="<?= $aEq[$j]["id"] ?>"><?= utf8_encode($aEq[$j]["nom"]); ?></option>
	<?php	} 
			else { ?>
				<option value="<?= $aEq[$j]["id"] ?>" selected><?= utf8_encode($aEq[$j]["nom"]); ?></option>
	<?php 	}
		} ?>
	</select>&nbsp;
	
<!-- EQUIP FIN -->
	
<!-- POSICIO INI -->
	<select name="pos">
		<option value="99">Posició</option>
	<?php  
		for ($j = 1 ; $j <= sizeof($POSICIONS) ; $j++) {
			if ($POSICIONS[$j] != $POSICIONS[$_POST["pos"]]) { ?>
				<option value="<?= $j ?>"><?= $POSICIONS[$j] ?></option>
	<?php	} 
			else { ?>
				<option value="<?= $j?>" selected><?= $POSICIONS[$j] ?></option>
	<?php 	}
		} ?>
	</select>&nbsp;
	
<!-- POSICIO FIN -->
	
<!-- PREU INI -->
	<select name="pr">
		<option value="99">Preu</option>
	<?php  
		for ($j = 0 ; $j < sizeof($aPreu) ; $j++) {
			if ($aPreu[$j]["valor"] != $_POST["pr"]) {
	?>			<option value="<?= $aPreu[$j]["valor"] ?>"><?= $aPreu[$j]["valor"] ?></option>
	<?php	} 
			else { ?><option value="<?= $aPreu[$j]["valor"] ?>" selected><?= $aPreu[$j]["valor"] ?></option>
	<?php 	}
		} ?>
	</select>&nbsp;
	
<!-- PREU FIN -->

	<input type="submit" value="Buscar">
	
	</div></td>
	<td><div align="left">
		<a href="imprim_preus.php" target="_blank"><img src='images/impresora2.jpg' alt="Image" style="width:20px;height:20px;" border='0'></a>
	</td>
</tr>


<?php

if (sizeof($consulta1) > 0) 
{ // si hem trobat files
		?>

		<table border="1" class="moduletable" align="center" width="100%">
		<th colspan="6"><div align="center">RESULTAT DE LA BÚSQUEDA</div></th>
		<tr>
		<td width="21%"><b>JUGADOR</b></td>
		<td width="11%"><b>EQUIP</b></td>
		<td width="11%"><b>PREU</b></td>
		<td width="11%"><b>POSICIÓ</b></td>
	<!--	<td width="12%"><b>BLOQ</b></td> -->
		<td width="11%"><b>PUNTS</b></td>
		<td width="11%"><b>MITJA</b></td> <!-- NOVA COLUMNA  --> 
		
		</tr>
<?php
		for($i = 0; $i < sizeof($consulta1); $i++) {
			/*$consulta2 = getQuiTeAquestJugador($idJug, $nomJug, $eqJug);   /* PER A OBTENIR LA MITJA DE PUTS PER PARTIT */
			$consulta2 = getQuiTeAquestJugador($consulta1[$i]["id"], utf8_encode($consulta1[$i]["nom"]), '');  /* crec que no fa falta passar l'equip ... pero tampoc el nom! sols és necessari el ID */
			/* la mitja està ací: $consulta2[0][4] */
?>
		<tr>

		   <td>
			  <?php if ($consulta1[$i]["ecomunitari"] == 1) { ?>
		      <a href="consultajug.php?e=<?= 100 ?>&j=<?=$consulta1[$i]["id"] ?>"><?=utf8_encode($consulta1[$i]["nom"]);?></a><font size="1" color="#FF0000"><b> &#9733;</b></font>
		      <?php } else { ?>
			  <a href="consultajug.php?e=<?= 100 ?>&j=<?=$consulta1[$i]["id"] ?>"><?=utf8_encode($consulta1[$i]["nom"]);?></a>
			  <?php } ?>
		   </td>
		   <td><?=$consulta1[$i]["sigles"] ?></td>
		   <td><?=$consulta1[$i]["valor"] ?></td>
		   <td><?=$POSICIONS[$consulta1[$i]["posicio"]] ?></td>
		   <td><?=$consulta1[$i]["punts"] ?></td>
		   <td><?=$consulta2[0][4] ?></td>                        <!-- sustituix a esta columna (BLOQUEJAT SÍ O NO): --> <?php /* <td><?=$ECOMUNITARI[$consulta1[$i]["ecomunitari"]] ?></td> */ ?>
		</tr>
<?php	}
?>
		</table>
		
	<?php 
	} 
	else { //sizeof = 0 ?>
	<br><div align="center"><font size = "2", color="#ff0000"><b>AJUSTA ELS CRITERIS DE SEL·LECCIÓ</b></font><br>
	<font color="#969696"><b>(Per a sel·leccionar tots els resultats posibles, no elegixques cap opció i clicka "Buscar")</b></font></div>
	<br>
	<?php 
} 
?>
<tr><td>&nbsp;</td></tr>

</table>
</form>
</html>
