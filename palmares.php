<?php
/************************************************
*	File: 	palmares.php					
*	Desc:   Palmares
*	Author:	Rubén Aparici						
************************************************/

include("config.php");
include("funcions2.php");

$anys = getTotalAnys();

?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<link href="estil.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />
</head>
<body>
<?php	
	for ($i = 0 ; $i < sizeof($anys); $i++) {
	$TaulaPalm = getTitolsHistoria($anys[$i]["any"]);
?>
	<P>
	<div align="left"><font color="#FF5500"><b><?= $anys[$i]["any"] - 1 ?> - <?= $anys[$i]["any"] ?></font></b></div>
	<table border="1" class="moduletable6" align="left">
<?php

	for($j = 0; $j < sizeof($TaulaPalm); $j++) {
					
		$infoCon = getNomComplet($TaulaPalm[$j]["idjug"]); 
						
?>
	<tr>		
		<td width="50%"><div align="left"><?= $TaulaPalm[$j]["competicio"] ?></div></td>
		<td width="50%"><div align="left"><?= utf8_encode($infoCon["name"]); ?></div></td>

	</tr>
<?php
	}
	?></table></P>
	<P>&nbsp;</P>
	<?php
	
}
?>


</body>
</html>
