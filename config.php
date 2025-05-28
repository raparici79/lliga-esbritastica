<?php
//Variables de configuración y otras cosas



//Variables base de datos
$BD_host = "localhost";
$BD_nombre = "wordpress_86";
$BD_user = "wordpress_65";
$BD_passwd = "Sebets_LE_2018";

// Taules
$TAULES_LE = array( 
				   'concursants', 
				   'configuracio', 
				   'copa', 
				   'enfrontaments', 
				   'enfrontaments_lfp', 
				   'equips', 
				   'jugadors', 
				   'jugadors_elegits', 
				   'mos_users', 
				   'palmares', 
				   'penalitzacio', 
				   'punts_jornada', 
				   'role');



//Vector de posicions
$POSICIONS[1] = "Porter";
$POSICIONS[2] = "Defensa";
$POSICIONS[3] = "Mig";
$POSICIONS[4] = "Davanter";

//Vector per mostrar si es ecomunitari o no
$ECOMUNITARI[0] = "No";
$ECOMUNITARI[1] = "Si";

//configuracio general
$NUM_JUGADORS = 22;
$PRESUPOST = 6500;
$NUM_ECOMUNITARIS = 22;
$NUM_ECOMUNITARIS_JUGANT = 22;
$NUM_MATEIX_EQUIP = 3;
$ADMINISTRADOR = 7;// el id de esbri

$JORNADA_BASE = 0; //a partir de la qual es copia la jornada

/* VARIABLES HISTÒRICA */
$NUM_TEMPORADES = 7; //Canviar el for en historica.php (i=2005;i<2005+$NUM_TEMPORADES;i++)
$TEMP_ACTUAL = 2011; //Canviar en actuhist.php

?>
