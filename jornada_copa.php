<?php
/************************************************
*	File: 	jornada_copa.php				*
*	Desc: 	Asigna una jornada a una ronda de copa		*
*	Author:	Rubén Aparici					*
************************************************/

session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];
if (!($userId = conValidat()) || $userType == "Registered")
	header("Location: login.php?url=jornada_copa.php");

if($_GET["jornada"] > 0)
	$jornada = (int) $_GET["jornada"];
else
	$jornada = 0;
	
//if ($jornada == 0)	$jornada = 1;

$rondes = array('SETZENS (ANADA)', 'SETZENS (TORNADA)', 'VUITENS (ANADA)', 'VUITENS (TORNADA)', 
                'QUARTS (ANADA)', 'QUARTS (TORNADA)', 'SEMIFINAL (ANADA)', 'SEMIFINAL (TORNADA)', 'FINAL');
				
				

if (isset($_POST["e"]))
{
	$ronda = $_POST["e"];
	$msg = asignaJornadaCopa($ronda, $jornada);
}

?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
<script language="javascript">
function canviaJornada() {
window.location = "jornada_copa.php?jornada=" + document.getElementById("jornada").value;
}
function editPunts(numJugadors) {
/*
	for (var i = 0; i < numJugadors; i++) {
		if (document.getElementById("punts" + i).value == "")
			document.getElementById("punts" + i).value = "0";
		
	}
*/
	document.all.formEdit.submit();
}
</script>
</head>
<body>
<table border="0" class="moduletable" align="center" width="100%">
<tr>
	<td><div align="center">
		
		<font color="#0066FF">ASSIGNA LA JORNADA </font>
		<br>
		<select id="jornada" name="jornada" onChange="javascript:canviaJornada()">
		<?php for ($i = 0 ; $i <= 38 ; $i++) { ?>
			<option value="<?= $i ?>" <?php if ($i == $jornada) echo "selected"; ?>>J <?= $i ?></option>
		<?php } ?>
		</select>
		</div>
	</td>
</tr>
</table>

<br>

<form name="formEdit" action="jornada_copa.php?jornada=<?= $jornada ?>" method="post">
<table border="0" class="moduletable" align="center" width="100%">


<tr>
	<td><div align="center">

<font color="#0066FF">A LA RONDA </font>
<br>
	<select name="e">
		<option value="0">&lt;-- Sel·lecciona una ronda --&gt;</option>
	<?php  
		for ($j = 0 ; $j < sizeof($rondes) ; $j++) {
	?>
		<option value="<?= $rondes[$j] ?>"><?= $rondes[$j] ?></option>
	<?php } ?>
	</select><br><br></div>
	</td>
</tr>

<tr><td></td></tr>
<tr><td colspan="3"><div align="center"><input type="submit" value="Guardar"></div>
<tr><td colspan="3"><div align="center">&nbsp;</div></tr></td>
<tr><td colspan="3"><div align="center"><font color="#FF0000"><?= $msg ?></font></div></tr></td>

</table>
</form>
<br><br>
	
<table border="1" class="moduletable" align="center" width="100%">
<th colspan="3"><div align="center">Assignades</div></th>
<tr><td width="50%"><div align="center"><b>Jornada</b></div></td><td width="50%"><div align="center"><b>Ronda</b></div></td></tr>
<?php
$assignades = consultaJornadaCopa($j);
for ($i = 0; $i < sizeof($assignades) ; $i++){?>
<tr><td><div align="center"><?= $assignades[$i]["jornada"] ?></div></td><td><div align="center"><?= $assignades[$i]["ronda"] ?></div></td></tr>
<?php } ?>
</table>

<br><br>
<div class="back_button"><a href="edit_copa.php">Enrere</a></div>
</body>
</html>
