<?php
/************************************************
*	File: 	edit_enfrontaments_lfp.php				*
*	Desc: 	edita els enfrontaments LFP			*
*	Author:	Rubén Aparici						*
************************************************/

session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];
	
if($_GET["jornada"] > 0)
	$jornada = (int) $_GET["jornada"];
else
	$jornada = getJornadaActual() + 3;
	
$aEq = getTaula("equips", "nom");

if ($_POST["e0"] > 0) { //actualitzem els enfrontaments
	$num = 20; //equips de primera divisió

	for ($i = 0 ; $i < $num ; $i+=2) {
		$eId1 = $_POST["e" . $i];
		$eId2 = $_POST["e" . ($i+1)];
		if ($eId1 > 0 && $eId2 > 0)
			addEnfrontamentLFP($eId1, $eId2, $jornada);		
	}
}

$jornadaEditada = jorLFPAmbEnfrontaments($jornada);


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
<script language="javascript">
function canviaJornada() {
window.location = "edit_enfrontaments_lfp.php?jornada=" + document.getElementById("jornada").value;
}

</script>
</head>
<body>

<?php
if (!($userId2 = conValidat())){
	echo "<b><font size=\"2\" color=\"#006600\">CONNECTA'T PER A AFEGIR JORNADES LFP</font></b>";
	?>
	<br><br>
	<div class="back_button"><a href="login1.php?url=edit_enfrontaments_lfp.php">Login</a></div>
	<?php
}
else {
?>
<table border="1" class="moduletable" align="center" width="100%">
<tr>
	<td><div align="center">
		Canviar a: 
		<select id="jornada" name="jornada" onChange="javascript:canviaJornada()">
		<?php for ($i = 1 ; $i <= 38 ; $i++) { ?>
			<option value="<?= $i ?>" <?php if ($i == $jornada) echo "selected"; ?>>Jornada <?= $i ?></option>
		<?php } ?>
		</select>
		</div>
	</td>
</tr>
</table>
<?php 
if ($jornadaEditada == 0) {
?>
<form name="formEdit" action="edit_enfrontaments_lfp.php?jornada=<?= $jornada ?>" method="post">
<table border="0" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">Enfrontaments LFP (J<?= $jornada ?>)</div></th>
<?php
	
	for ($i = 0 ; $i < sizeof($aEq) ; $i += 2) {
 ?>
<tr>
	<td><div align="center">

	<select name="e<?= $i ?>">
		<option value="0">&lt;-- Selecciona Equip --&gt;</option>
	<?php  
		for ($j = 0 ; $j < sizeof($aEq) ; $j++) {
	?>
		<option value="<?= $aEq[$j]["id"] ?>"><?= $aEq[$j]["nom"] ?></option>
	<?php } ?>
	</select></div>
	</td>
	<td>Vs.</td>
	<td><div align="center">
	<select name="e<?php echo ($i+1); ?>">
		<option value="0">&lt;-- Selecciona Equip --&gt;</option>
	<?php  
		for ($j = 0 ; $j < sizeof($aEq) ; $j++) {
	?>
		<option value="<?= $aEq[$j]["id"] ?>"><?= $aEq[$j]["nom"] ?></option>
	<?php } ?>
	</select></div>
	</td>
</tr>
<tr><td></td></tr>
<?php
	}
?>
<tr><td colspan="3"><div align="center"><input type="submit" value="Guardar"></div></td></tr>
</table>
</form>
<font color="#FF0000">ATENCIÓ!!!Una volta guardada la jornada NO pordrás modificar-la, assegurat q tot està correcte abans de guardar! Bueno.. si la lies, m'avises i l'elimine ;)</font>
<?php } 
else { 
	echo "<b><font size=\"2\" color=\"#006600\">Aquesta jornada ja està editada. Sel·lecciona una altra. Gràcies!!</font></b>";
	}

}

?>
<br><br>

<!--nou -->
<?php	
	
	for ($i = $jornada; $i <= 38; $i++) {
	$jorLFP = $i;
	$TaulaLFP = getEnfrontamentsLFP($jorLFP);
?>
	<P>
	<table border="1" class="moduletable3" align="center" width="100%">
	<th colspan="10"><div align="center">Jornada <?= $jorLFP ?></div></th>
	<tr>
	<?php for ($it = 0 ; $it < 10 ; $it++) { ?>
		<td width="10%" height="40"><center>
		<?php 
			echo "<img src='LFP_mini/".$TaulaLFP[$it]["idEquip1"].".png' border='0'>"; 		
		?>
		</center>
		</td>
	<?php } ?>	
	</tr>
	
	<tr>
	<?php for ($it = 0 ; $it < 10 ; $it++) { ?>
	
			<td width="10%" height="40">
		<?php 
			echo "<img src='LFP_mini/".$TaulaLFP[$it]["idEquip2"].".png' border='0'>"; 		
		?>
		</td>
	<?php } ?>	
	</tr>
	
	</table></P><?php
}
?>
<!--nou -->
<div class="back_button"><a href="login1.php?tancar=si">Tancar</a></div>

</body>
</html>
