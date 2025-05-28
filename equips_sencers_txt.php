<?php
header('Content-type: text/plain; charset="utf-8"');
header('Content-Disposition: attachment; filename="equips.txt"');

include("config.php");
include("funcions2.php");

echo "WOOOOP!\n...\n\n";

echo enviarEquipsSencers();

?>
