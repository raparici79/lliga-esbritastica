<?php
/************************************************
*	File: 	login2.php						*
*	Desc: 	login per no mostrar preus a morosos*
*	Author:	Rubén Aparici 						*
************************************************/
session_start();
include("config.php");
include("funcions.php");

?>
<?php
$msg = "";
$laUrl = $_GET["url"];
$esPrivado = $laUrl == "ini_jornada.php" || $laUrl == "edit_jugadors.php"  || $laUrl == "edit_enfrontaments.php" || $laUrl == "penalitzar.php"; 
if (isset($_POST["username"]) && $_POST["username"] != "") { //iniciem la sessio

	if ($conId = concursantValid($_POST["username"], $_POST["passwd"])) {
	    if (!$esPrivado || strtoupper($_POST["username"]) == "ESBRI" || strtoupper($_POST["username"]) == "RUBEN")
	    {
		$_SESSION["userid"] = $conId;
		$_SESSION["usertype"] = getUserType($_POST["username"]);
		header("Location: " . $_GET["url"]);
		/*$msg = "<font color=\"#006600\">Connectat correctament :)</font>";*/
	    }
	    else
		$msg = "<font color=\"#ff0000\">No tens perm&iacute;s!!!</font>";
	}
	else
		$msg = "<font color=\"#ff0000\">Les dades no s&oacute;n correctes</font>";

}

if (isset($_GET["tancar"]))
	tancarSessio();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<link href="../templates/rhuk_solarflare_ii/css/template_css.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
</head>
<body>
<table align="center" border="1" class="moduletable">
	<th align="center">USUARI I CONTRASENYA DE GESTIO DE L'EQUIP</th>
	<tr>
	<td>
	<form action="login2.php?url=<?= $_GET["url"] ?>" method="post">
	Usuari <input name="username" type="text" class="inputbox" alt="username" size="10" /> 
	Contrasenya <input type="password" name="passwd" class="inputbox" size="10" alt="password" />
	<input type="submit" value="Entrar">
	</form>
	</td>
	</tr>
	<tr>
	<td>
	<?= $msg ?>
	</td>
	</tr>
</table>
</body>
</html>
