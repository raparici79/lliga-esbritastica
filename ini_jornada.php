<?php
/************************************************
*	File: 	elegir_jugador.php					*
*	Desc: 	anyadix jugador a la llista			*
*	Author:	Jose Gargallo 						*
************************************************/
session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];

if (!($userId = conValidat() || $userType == "Registered"))
	header("Location: login2.php?url=ini_jornada.php");

if ($userType != "Administrator" && $userType != "Super Administrator")
{
echo "HAS DE SER ADMINISTRADOR PER UTILITZAR AQUESTES FUNCIONS";
exit;
}

$jornadaActual = getJornadaActual();
if (isset($_GET["activar"]) && $_GET["activar"] == "si") { //posicio correcta
	activarJornada($jornadaActual + 1, $_GET["limit"]);
}
$jornadaActual = getJornadaActual();
$limit = getValorConfiguracio("data_limit");
?>
<html>
<head>
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
<script language="javascript">
function activar() {
	if (confirm("Vols activar la següent jornada?"))
		window.location = "ini_jornada.php?activar=si&limit=" + document.getElementById("limite").value;
}
</script>
</head>
<body>
<font color="#006600"><b>******************************************************************************</b></font>
<font color="#006600"><b></b></font><br><a href="backup_sql.php">IMPORTANT: CLICK ACÍ PER A FER COPIA DE SEGURETAT</a><font color="#006600"><b></b>
<br>
******************************************************************************</b></font><br>
<br>
<table align="center" border="1" class="moduletable">
	<th align="center" colspan="4">Activació de la següent jornada</th>
	<tr>
	<td><div align="center"><b>Jornada Actual: <?= $jornadaActual ?></b></div></td>
	<td><div class="back_button"><input type="text" name="limite" id="limite" maxlength="16" value="<?= getValorConfiguracio("data_limit") ?>"> <a href="javascript:activar()">Activar jornada <?= $jornadaActual + 1 ?></a></div></td>
	</tr>
	<tr>
	<td colspan="2">
	<font color="#FF0000"><b>* ATENCIÓ! Activant una nova jornada no és possible editar la anterior (Activar quan finalitze la jornada)</b></font><br>
	<font color="#FF0000"><b>** ATENCIÓ!!! el format de l'hora límit a de ser OBLIGATORIAMENT: <i>dd/mm/aaaa-hh:mm</i> de lo contrari NO FUNCIONARÀ!</b></font>
	<br><br>
	<?php
		if (isset($_GET["activar"]) && $_GET["activar"] == "si")
			echo "Jornada $jornadaActual activada correctament";
	?>
	</td>
	</tr>
</table>
<div class="back_button"><a href="login2.php?tancar=si">Tancar</a></div>
</body>
</html>
