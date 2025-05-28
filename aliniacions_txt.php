<?php
header('Content-type: text/plain; charset="utf-8"');
header('Content-Disposition: attachment; filename="aliniacions.txt"');

include("config.php");
include("funcions.php");

$jornadaActual = getJornadaActual();

echo "AHI VAN:\n...\n\n";
if (foraLimitRivals())
{
  echo enviarAliniacions($jornadaActual);
}
?>
