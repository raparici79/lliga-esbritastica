<?php
/************************************************
*	File: 	historica.php						*
*	Desc: 	Dades històriques					*
*	Author:	Rubén Aparici 						*
************************************************/

include("config.php");
include("funcions2.php");

$aAny = 'Històrica';
if($_GET["any"] == 'HistExtra' || ($_GET["any"] > 2000  && $_GET["any"] <3000 )) {
	$aAny = $any;
}
else {
	$aAny = 'Històrica';
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
<script language="javascript">
function canviaany() {
window.location = "historica.php?any=" + document.getElementById("any").value;
}
</script>

</head>
<body>

<table border="1" class="moduletable" align="center" width="100%">
<th><?= $aAny ?></th>
<tr>
	<td><div align="center">
		Canviar a: 
		<select id="any" name="any" onChange="javascript:canviaany()">
		
		<?php $i = 'Històrica' /*puc provar esto a vore... i quan siga 0000 mostrar històrica*/ ?>
			<option value="<?= $i ?>" <?php if ($i == $aAny) echo "selected"; ?>><?= $i ?></option>
			
		<?php $i = 'HistExtra' /*puc provar esto a vore... i quan siga 9999 mostrar històrica extrapolada*/ ?>
			<option value="<?= $i ?>" <?php if ($i == $aAny) echo "selected"; ?>><?= $i ?></option>	
	
		<?php for ($i = 2004 ; $i <= $TEMP_ACTUAL ; $i++) { ?>
			<option value="<?= $i ?>" <?php if ($i == $aAny) echo "selected"; ?>><?= $i ?></option>
		<?php } /* puc afegir ací: <option value="<?=     */?>
		</select>
		</div>
	</td>
</tr>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">Classificació Acumulada</div></th>
<tr><td width="25%"><b>Nom</b></td><td width="50%"><CENTER><b>NºTemporades / Debut / Puesto</b></CENTER></td><td width="25%"><b>Punts</b></td></tr>
<?php
	$aTipoClass = 'Acumulada'; 
	$Classificacions = getClassHistoriques($aAny,$aTipoClass); //si l'any es 0000, la funció farà un tractament distint.
	for($i = 0; $i < sizeof($Classificacions); $i++) {
?>
		<tr><td><?= $i+1 ?>.- <?=$Classificacions[$i]["0"] ?></td><td><?= $Classificacions[$i]["nom_equip"] ?></td>
		<td><?= $Classificacions[$i]["1"] ?><?php if ($aAny == 'HistExtra') echo "%"; ?></td></tr>
<?php
	}
?>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="4"><div align="center">Classificació General</div></th>
<tr><td width="25%"><b>Nom</b></td><td width="50%"><b>Equip</b></td><td width="15%"><b>Punts</b></td><td width="10%"><b>Puntaverage</b></td></tr>
<?php
	$aTipoClass = 'General'; 
	$Classificacions = getClassHistoriques($aAny,$aTipoClass); 
	for($i = 0; $i < sizeof($Classificacions); $i++) {         
?>
		<tr><td><?= $i+1 ?>.- <?=$Classificacions[$i]["0"] ?></td><td><?= $Classificacions[$i]["nom_equip"] ?></td>
		<td><?= $Classificacions[$i]["1"] ?><?php if ($aAny == 'HistExtra') echo "%"; ?></td>
		<td><?php if ($Classificacions[$i]["2"] > 0) echo "+"; ?><?= $Classificacions[$i]["2"] ?></td>
		</tr>
<?php
	}
?>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">Classificació Copa</div></th>
<tr><td width="25%"><b>Nom</b></td><td width="75%"><b>Equip</b></td></tr>
<?php
	$aTipoClass = 'Copa'; 
	//$Classificacions = getClassHistoriques($aAny,$aTipoClass); // DE MOMENT no la calculem
	$Classificacions = NULL; //LLevar esta linea quan decifim penjar la COPA HISTORICA
	for($i = 0; $i < sizeof($Classificacions); $i++) {
?>
		<tr><td><?= $i+1 ?>.- <?=$Classificacions[$i]["0"] ?></td><td><?= $Classificacions[$i]["nom_equip"] ?></td></tr>
<?php
	}
?>
</table>

</body>
</html>


