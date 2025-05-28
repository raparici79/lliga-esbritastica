<?php
include("config.php");
include("funcions.php");

$taula = getPuntsGeneralOptima();
for ($i = 0 ; $i < sizeof($taula) ; $i++) {
	echo ($i+1) . ". " . $taula[$i]["nom"] . " -> " . $taula[$i]["punts"] . " (" . $taula[$i]["puntaverage"] . ")\n";
}
?>
