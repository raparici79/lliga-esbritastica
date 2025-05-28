<?php
/********************************
*	File: 	lfp.php				*
*	Desc: 	Enfrontaments LFP	*
*	Author:	Rubén Aparici		*
********************************/
session_start();
include("config.php");
include("funcions2.php");

$jornadaActual = getJornadaActual();
$jornadaLFP = $jornadaActual + 3;
$enfrontLFP = getEnfrontamentsLFP($jornadaLFP);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" /> 
</head>

<body>

<table border="1" class="moduletable3" align="center" width="100%">
<tr>
<?php for ($it = 0 ; $it < 10 ; $it++) { ?>
	<td width="10%" height="40"><center>
	<?php 
		echo "<img align=center img src='LFP_mini/".$enfrontLFP[$it]["idEquip1"].".png' border='0'>"; 		
	?>
	</center>
	</td>
<?php } ?>	
</tr>

<tr>
<?php for ($it = 0 ; $it < 10 ; $it++) { ?>

		<td width="10%" height="40">
	<?php 
		echo "<img src='LFP_mini/".$enfrontLFP[$it]["idEquip2"].".png' border='0'>"; 		
	?>
	</td>
<?php } ?>	
</tr>

</table>

</body>
</html>
