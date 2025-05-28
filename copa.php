<?php
/************************************************
*	File: 	copa.php					
*	Desc: 	Enfrontaments de copa.
*	Author:	Rubén Aparici						
************************************************/

include("config.php");
include("funcions2.php");

$self = $_SERVER['PHP_SELF']; //Obtenemos la página en la que nos encontramos
header("refresh:300; url=$self"); //Refrescamos cada 300 segundos

$rondes = array('SETZENS (ANADA)', 'SETZENS (TORNADA)', 'VUITENS (ANADA)', 'VUITENS (TORNADA)', 
                'QUARTS (ANADA)', 'QUARTS (TORNADA)', 'SEMIFINAL (ANADA)', 'SEMIFINAL (TORNADA)', 'FINAL');

?>


<html>
<head>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<link href="../templates/rhuk_solarflare_ii/css/template_css.css" rel="stylesheet" type="text/css"/> -->
<link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" />

<script language="javascript">
function openEnfrontament(id1, id2, j) {
window.open("enfront_copa.php?id1=" + id1 + "&id2=" + id2 + "&j=" + j, "enfrontament", 'toolbar=0');
}
</script>
</head>

<body>

<?php
for($r = 0; $r < 9; $r++) { //4 rondes d'anada i 4 de tornada + la FINAL
	$enfront = NULL; //No sé si és necessari
	$enfront2 = NULL; //No sé si és necessari
	$jornada = NULL; //No sé si és necessari
	$comentaris = NULL; //No sé si és necessari
	
	$jornada = consultaJornadaCopa($rondes[$r]);
	$comentaris = consultaComentariCopa($rondes[$r]);
?>
<!-- ------------- bloc de ronda ----------------- -->
<table border="1" class="moduletable" align="center" width="100%">
<th colspan="5"><div align="center"><?= $rondes[$r] ?> >> J<?= $jornada ?></div></th>

					
<tr><td width="35%"><b><div align="right">Equip 1</div></b></td><td width=""><div align="center"><b>Punts</b></div></td><td width=""><div align="center"><b>  </b></div></td><td width=""><b><div align="center">Punts</b></div></td><td width="35%"><b>Equip 2</b></td></tr>
<?php
	$enfront = getEnfrontamentsCopa($rondes[$r]); //<!--Haurem de fer una nova funció: getEnfrontamentsCopa   -->
	if ($r == 1 or $r ==3 or $r ==5 or $r ==7){
		$enfront2 = getEnfrontamentsCopa($rondes[$r-1]);
	}

	for($i = 0; $i < sizeof($enfront); $i++) {
?>
		
		<tr>
		<td>
		<?php if ($r == 1 or $r ==3 or $r ==5 or $r ==7){
		          $total1 = $enfront[$i][0]["punts"] + $enfront2[$i][0]["punts"];
				  $total2 = $enfront[$i][1]["punts"] + $enfront2[$i][1]["punts"];
				  
				  if ($total1 < $total2){				  
				  ?>	
					<div align="right"><strike><?=utf8_encode($enfront[$i][0]["nom"]); ?></strike></div></td><td><div align="center"><?=$enfront[$i][0]["punts"]?> (<?=$total1?>)</div>
				  <?php }  else{  ?>
				    <div align="right"><?=utf8_encode($enfront[$i][0]["nom"]); ?></div></td><td><div align="center"><?=$enfront[$i][0]["punts"]?> (<?=$total1?>)</div>
				  <?php } 
		
		
		      } else { ?>
		<div align="right"><?=utf8_encode($enfront[$i][0]["nom"]); ?></div></td><td><div align="center"><?=$enfront[$i][0]["punts"] ?></div>
		<?php } ?>
		</td>
		<td>
		<?php if (foraLimitRivals() || $jornada < getJornadaActual()) { ?>		
		<div align="center"><a href="javascript:openEnfrontament(<?=$enfront[$i][0]["id"] ?>,<?=$enfront[$i][1]["id"] ?>,<?=$jornada ?>)"><div align="center"><img src='images/ojoo1.png' alt="Image" style="width:15px;height:11px;" border='0'></div></a></div></td>
		<?php } ?>
		</td>
		<td>
		<?php if ($r == 1 or $r ==3 or $r ==5 or $r ==7){ 
				  if ($total2 < $total1){	
				  ?>
				    <div align="center"><?=$enfront[$i][1]["punts"] ?> (<?=$total2?>)</div></td><td><div align="left"><strike><?=utf8_encode($enfront[$i][1]["nom"]); ?></strike></div>
				  <?php }  else{  ?>
				    <div align="center"><?=$enfront[$i][1]["punts"] ?> (<?=$total2?>)</div></td><td><div align="left"><?=utf8_encode($enfront[$i][1]["nom"]); ?></div>
				  <?php } 	
		
		      } else { ?>
		<div align="center"><?=$enfront[$i][1]["punts"] ?></div></td><td><div align="left"><?=utf8_encode($enfront[$i][1]["nom"]); ?></div>
		<?php } //#006600 ?>
		</td>
		
		
		
		
<?php
	}
?>

</table>

<font color="#FF0000"><b><?= $comentaris ?></b></font>
<br>
<br>
<br>

<!-- ------------- bloc de ronda ----------------- -->
<?php
}
?>


</body>
</html>








