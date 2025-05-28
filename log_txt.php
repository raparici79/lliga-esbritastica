<?php
header('Content-type: text/plain; charset="utf-8"');

include("config.php");
include("funcions2.php");

$c = (int) $_GET["c"];
$infoCon = getInfoConcursant($c); 
$jornadaActual = getJornadaActual();

$nom_c = $infoCon["nom"];

if (foraLimitRivals())
{
	$nom_arxiu = quitarAcentos("$nom_c");
	$nom_arxiu .= ".txt";
	$log_txt = "LOGS/".$nom_arxiu;
	
	header ("Content-Disposition: attachment; filename=".$nom_arxiu." "); 
	
	header ("Content-Type: application/octet-stream");

	header ("Content-Length: ".filesize($log_txt));

	readfile($log_txt);
}
else echo "FINS QUE NO COMENCE LA JORNADA, NO POTS CONSULTAR ELS LOGS (PILLÍN, PILLÍN..)";
  
?>
