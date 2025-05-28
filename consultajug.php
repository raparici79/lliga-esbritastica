<?php
/************************************************
*	File: 	consultajug.php						*
*	Desc: 	Consulta estadístiques d'un jugador *
*	Author:	Rubén Aparici 						*
************************************************/
session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];
$jornada = getJornadaActual();
if ($jornada == 0 && $userType != "Super Administrator")
{
echo "PILLIN PILLIN!!!!";
exit;
}

$aCon = getTaula("equips", "nom");

if (isset($_POST["j"]) && $_POST["j"] != 'Jugador' && $_POST["j"] > ' ')
	$consulta1 = getEsNomUnic($_POST["j"], $_POST["e"]);// també obtenim el id del jugador. 
else
	$consulta1 = getEsNomUnic($_GET["j"], $_GET["e"]);// també obtenim el id del jugador. 

?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
</head>
<body>
<br>
<form name="formEdit" action="consultajug.php?" method="post">
<table border="0" class="moduletable2" align="center" width="100%">
<div align="center"> </div>
<tr>
	<td><div align="center">

	<input type="hidden" name="e" value="99">
	<?php
		if (sizeof($consulta1) > 1){ ?>
	      <input type="text" value="Jugador" name="j" size="10">
	<?php
		}
		else{ ?>
		   <input type="text" value="<?= utf8_encode($consulta1[0][1]); ?>" name="j" size="10">
	<?php
		} ?>
	<input type="submit" value="Buscar">
	</div>
	<center><font color="#000000"></font></center>
	</td>
</tr>


<?php

if (sizeof($consulta1) > 1)   { ?>		 

	<table border="0" class="moduletable" align="center" width="100%">

			<tr><th colspan="4"><div align="center"><font size = "2", color="#000000">S'HA TROBAT MÉS D'UNA COINCIDENCIA</font></div></th></tr>
			
		<?php	for ($i = 0; $i < sizeof($consulta1); $i++)  { ?>		
					<tr><th colspan="4"><div align="center">
					<a href="consultajug.php?e=<?= 100 ?>&j=<?=$consulta1[$i][0] ?>">
					<?=utf8_encode($consulta1[$i][1]);?> (<?=utf8_encode($consulta1[$i][2]);?>)</a></tr></div></th>
			<?php } ?> 
			<br>
			<tr><th colspan="4"><div align="center"><font size = "2", color="000000">CONCRETA MÉS LA BÚSQUEDA O CLICKA UN RESULTAT</font></div></th></tr>
	
<?php 
} 

else{
	if (sizeof($consulta1) > 0) 
	{ // si hem trobat 1 fila  	
		$idJug = $consulta1[0][0];
		$nomJug = utf8_encode($consulta1[0][1]);  //l'obtingut per la consulta no estarà a majúscules... perfecte.
		$eqJug = $consulta1[0][2];
		$jPreu = $consulta1[0][3];
		$eqJugSigles = $consulta1[0][4];
		$consulta2 = getQuiTeAquestJugador($idJug, $nomJug, $eqJug);   ?>

		<table border="1" class="moduletable" align="center" width="100%">
		<tr>		
		<td width="16%"><center><b>Equip</b></center></td>
		<td width="10%"><center><b>Punts</b></center></td>
		<td width="10%"><center><b>Partits</b></center></td>
		<td width="12%"><center><b>Mitja</b></center></td>
		<td width="12%"><center><b>Preu</b></center></td>
		<td width="24%"><center><b>Propietaris (<?= $consulta2[0][5] ?>) </b></center></td>
		
		</tr>
		<tr>		   
		   <td><center><?= $eqJug ?></center></td>
		   <td><center><?= $consulta2[0][2] ?></center></td>
		   <td><center><?= $consulta2[0][3] ?></center></td>
		   <td><center><?= $consulta2[0][4] ?></center></td>
		   <td><center><?= $jPreu ?></center></td>
		   <td><center>
		<?php
			$hora_inici = foraLimitRivals();
			for($i = 0; $i < $consulta2[0][5]; $i++) { 
				if ($consulta2[$i][1] == 1 && $hora_inici) {
			?>
				<?= utf8_encode($consulta2[$i][0]); ?><br>
		<?php	}
				else {
			?>
				<font color="#C0C0C0"><?= utf8_encode($consulta2[$i][0]); ?> </font> <br>	
		<?php		}}
		?>
		</center>
		</td>
		</tr>
		</table>
		* En <font color="#969696"><b>gris </b></font>apareixen els concursants que no han alineat el jugador aquesta jornada.<br>
	<?php 
	} 
else { //sizeof = 0 ?>
	<tr><td>&nbsp;</td></tr>
		<tr><th colspan="4"><div align="center"><font size = "2", color="#ff0000">CAP COINCIDÈNCIA</font></div></th></tr>
	<?php 
	} 
} 
?>
<tr><td>&nbsp;</td></tr>
<tr><td colspan="4"><div align="center"></div>

</table>
</form>

<?php if ($idJug > 0) {
	$puntsJug = getPuntsJug($idJug);
	$enfrontLFP = getEnfrontamentsLFP_jug_eq($consulta1[0][5]);
	?>
	<P>
	<table border="1" class="moduletable2" align="left">
	<th colspan="4"><div align="center">Punts per Jornada de <?= utf8_encode($consulta1[0][1]); ?> (<?= $POSICIONS[$consulta1[0][6]]; ?>)</div></th>
<?php
	$pica = "&#9828; ";
	$pica_ko = "&#9760; ";
/*	$pica = "(+) ";*/
	
	$jor = 0;
	for($j = 0; $j < $jornada; $j++) {	
	if ($puntsJug[$jor]["jornada"] == $j+1)
	{
		$p = $puntsJug[$jor]["punts"];
		$jor++;
		$X = " ";
		for($k = 0; $k < $p; $k++) {$X = $X.$pica;}
		for($k = 0; $k > $p; $k--) {$X = $X.$pica_ko;}
	}
	else {$p = ' '; $X = ' ';}
?>
	<tr>
		<td width="9%"><div align="center">J<?= $j+1 ?></div></td>
		<td width="24%"><div align="center">
		<?php 
			echo "<img align=center img src='LFP_mini_2/".$enfrontLFP[$j+4][0].".png?' border='0'> vs <img align=center img src='LFP_mini_2/".$enfrontLFP[$j+4][1].".png?' border='0'>"; 		
		?>
		</div>
		</td>
		<td width="7%"><div align="center"><?php if ($p < 0) { ?><font color="#FF0000"><?php } else{?><font color="#04B404"><?php } ?><?= $p ?></div></td>
		<td width="60%"><div align="left"><?php if ($p < 0) { ?><font size="2" color="#FF0000"><?php } else{?><font size="2" color="#04B404"><?php } ?><?= $X ?></div></td>
	</tr>
<?php
	}
	?></table></P>
<?php
}
?>

</html>
