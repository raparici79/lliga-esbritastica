<?php
/************************************************
*	File: 	conectat.php						*
*	Desc: 	avis de ja connectat				*
*	Author:	Rubén Aparici 						*
************************************************/
session_start();
include("config.php");
include("funcions2.php");
$userType = $_SESSION["usertype"];
if (!($userId2 = conValidat()))
	header("Location: login2.php?url=conectat.php");
$jornadaActual = getJornadaActual();$escopa = EsJornadaCopa($jornadaActual);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" /> 

</head>
<body>

<font color="#006600"><b>ESTAS CONNECTAT I POTS ADMINISTRAR EL TEU EQUIP</b></font>
<br><P><?php if ($escopa > 0)  echo "<font size=\"6\" color=\"#ff0000\">AQUESTA JORNADA HI HA COPA</font>";?></P>
<br>
<div class="back_button"><a href="login2.php?tancar=si">Tancar</a></div> &nbsp;&nbsp;&nbsp;
</body>
</html>
