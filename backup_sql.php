<?php
/************************************************
*	File: 	backup_sql.php						*
*	Desc: 	backup taules LFE	*
*	Author:	Rubén Aparici 						*
************************************************/

session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];

if (!($userId = conValidat() || $userType == "Registered"))
	header("Location: login2.php?url=backup_sql.php");

if ($userType != "Administrator" && $userType != "Super Administrator")
{
echo "HAS DE SER ADMINISTRADOR PER UTILITZAR AQUESTES FUNCIONS";
exit;
}



/* Determina si la tabla será vaciada (si existe) cuando  restauremos la tabla. */            
$drop = true;
$tablas = false; //tablas de la bd
// Tipo de compresion.
// Puede ser "gz", "bz2", o false (sin comprimir)
 
$compresion = false;
 
/* Conexion */
/*$conexion = mysql_connect($host, $usuario, $passwd)
or die("No se puede conectar con el servidor MySQL: ".mysql_error());
mysql_select_db($bd, $conexion)
or die("No se pudo seleccionar la Base de Datos: ". mysql_error());
*/
$conexion = conectar(); // /*RAB*/ sustituix lo de dalt
mysql_select_db($BD_nombre, $conexion)
or die("No se pudo seleccionar la Base de Datos: ". mysql_error());



	/* Se busca las tablas en la base de datos *
if ( empty($tablas) ) {
    $consulta = "SHOW TABLES FROM $BD_nombre;";
    $respuesta = mysql_query($consulta, $conexion)
    or die("No se pudo ejecutar la consulta: ".mysql_error());
    while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
        $tablas[] = $fila[0];		
    }
}
*/



//$nombre="backup_sql.txt"; //Este es el nombre del archivo a generar
$nombre="backup_bbdd_".date("Ymd")."_".date("hms").".txt";//Este es el nombre del archivo a generar

/* Se crea la cabecera del archivo */
$info['dumpversion'] = "1.1b";
$info['fecha'] = date("d-m-Y");
$info['hora'] = date("h:m:s A");
$info['mysqlver'] = mysql_get_server_info();
$info['phpver'] = phpversion();
ob_start();
print_r($TAULES_LE);
$representacion = ob_get_contents();
ob_end_clean ();
preg_match_all('/(\[\d+\] => .*)\n/', $representacion, $matches);
$info['TAULES_LE'] = implode(";  ", $matches[1]);
$dump = <<<EOT
# +===================================================================
# |
# | Generado el {$info['fecha']} a las {$info['hora']} 
# | Servidor: {$_SERVER['HTTP_HOST']}
# | MySQL Version: {$info['mysqlver']}
# | PHP Version: {$info['phpver']}
# | Base de datos: '$bd'
# | Tablas: {$info['TAULES_LE']}
# |
# +-------------------------------------------------------------------
 
EOT;
foreach ($TAULES_LE as $tabla) {
    
    $drop_table_query = "";
    $create_table_query = "";
    $insert_into_query = "";
    
    /* Se halla el query que será capaz vaciar la tabla. */
    if ($drop) {
        $drop_table_query = "DROP TABLE IF EXISTS `$tabla`;";
    } else {
        $drop_table_query = "# No especificado.";
    }
 
    /* Se halla el query que será capaz de recrear la estructura de la tabla. */
    $create_table_query = "";
    $consulta = "SHOW CREATE TABLE $tabla;";
    $respuesta = mysql_query($consulta, $conexion)
    or die("No se pudo ejecutar la consulta: ".mysql_error());
    while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
            $create_table_query = $fila[1].";";
    }
    
    /* Se halla el query que será capaz de insertar los datos. */
    $insert_into_query = "";
    $consulta = "SELECT * FROM $tabla;";
    $respuesta = mysql_query($consulta, $conexion)
    or die("No se pudo ejecutar la consulta: ".mysql_error());
    while ($fila = mysql_fetch_array($respuesta, MYSQL_ASSOC)) {
            $columnas = array_keys($fila);
            foreach ($columnas as $columna) {
                if ( gettype($fila[$columna]) == "NULL" ) {
                    $values[] = "NULL";
                } else {
                    $values[] = "'".mysql_real_escape_string($fila[$columna])."'";
                }
            }
            $insert_into_query .= utf8_encode("INSERT INTO `$tabla` VALUES (".implode(", ", $values).");\n");
            unset($values);
    }
    
$dump .= <<<EOT
 
# | Vaciado de tabla '$tabla'
# +------------------------------------->
$drop_table_query
 
 
# | Estructura de la tabla '$tabla'
# +------------------------------------->
$create_table_query
 
 
# | Carga de datos de la tabla '$tabla'
# +------------------------------------->
$insert_into_query
 
EOT;
}


/* Envio */
if ( !headers_sent() ) {
    header("Pragma: no-cache");
    header("Expires: 0");
    header("Content-Transfer-Encoding: binary");
    switch ($compresion) {
    case "gz":
        header("Content-Disposition: attachment; filename=$nombre.gz");
        header("Content-type: application/x-gzip");
        echo gzencode($dump, 9);
        break;
    case "bz2": 
        header("Content-Disposition: attachment; filename=$nombre.bz2");
        header("Content-type: application/x-bzip2");
        echo bzcompress($dump, 9);
        break;
    default:
        header("Content-Disposition: attachment; filename=$nombre");
        header("Content-type: application/force-download");
        echo $dump;
    }
} else {
    echo "<b>ATENCION: Probablemente ha ocurrido un error</b><br />\n<pre>\n$dump\n</pre>";
}
 
?>