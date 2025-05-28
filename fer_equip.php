<?php
/************************************************
*	File: 	llistat_jugadors.php				*
*	Desc: 	Llista els equips i jugadors.		*
*	Author:	Jose Gargallo 						*
************************************************/
session_start();
include("config.php");
include("funcions2.php");

$userType = $_SESSION["usertype"];
$jornada = getJornadaActual();

if (!($userId2 = conValidat()))
	header("Location: login1.php?url=fer_equip.php");	

/*21/5/2016*/ else {

$userId = $userId2;
if ($userType != "Registered" && $_GET["c"] > 0)
	$userId = (int) $_GET["c"];

$jornadaActual = getJornadaActual();
$escopa = EsJornadaCopa($jornadaActual);
$infoCon = getInfoConcursant($userId); 

/*RAB*/$infoCon2 = getInfoConcursant($userId2);

if (isset($_GET["j"]) && $_GET["j"] > 0 && !foraLimit()) { //hi ha canvi de jugador
	$v = $_GET["v"];
	$c = $_GET["c"];
	$j = $_GET["j"];
	$jo = $_GET["jo"];
	if (($v == 0 || $v == 1) && ($userId2 == $c ||  $userType != "Registered")) {
		$msg1 = canviaSeleccioJugador($c, $j, $jo, $v, $infoCon["estrategia"]);
/*RAB*/	$msg2 = EscriuLOG ('seleccio', $j, $v, $infoCon["nom"], $infoCon2["nom"]);
	}
}

if (isset($_GET["op"]) && $_GET["op"] == "addJugador" && (($jornadaActual == $JORNADA_BASE  && !foraLimit()) || $userType == "Administrator" || $userType == "Super Administrator")) {
	$addJugadorId = (int) $_GET["id"];
	$c = $_GET["c"];	
	if ($addJugadorId > 0 && ($userId2 == $c ||  $userType != "Registered")) {
		//jornada 0 indica que no ha comensat la lliga i s'estan fent els equips
		$msg1 = addJugador($infoCon["id"], $addJugadorId, $JORNADA_BASE, 0);
/*RAB*/	$msg2 = EscriuLOG ('AfegeixJug', $addJugadorId, $v, $infoCon["nom"], $infoCon2["nom"]);

		if ($jornadaActual != $JORNADA_BASE)
			$msg1 = addJugador($infoCon["id"], $addJugadorId, $jornadaActual, 0);
	}
}

if (isset($_GET["op"]) && $_GET["op"] == "anularJugador" && (($jornadaActual == $JORNADA_BASE  && !foraLimit()) || $userType == "Administrator" || $userType == "Super Administrator")) {
	$anularJugadorId = (int) $_GET["id"];
	$c = $_GET["c"];
	if ($anularJugadorId > 0 && ($userId2 == $c ||  $userType != "Registered")) {
		//jornada 0 indica que no ha comensat la lliga i s'estan fent els equips
		$msg1 = anularJugador($infoCon["id"], $anularJugadorId, 0);
/*RAB*/	$msg2 = EscriuLOG ('AnulaJug', $anularJugadorId, $v, $infoCon["nom"], $infoCon2["nom"]);
		if ($jornadaActual != $JORNADA_BASE)
			$msg1 = anularJugador($infoCon["id"], $anularJugadorId, $jornadaActual);
	/*RAB*/	$msg2 = EscriuLOG ('AnulaJug', $anularJugadorId, $v, $infoCon["nom"], $infoCon2["nom"]);
	}
}

if (isset($_GET["s"]) && $_GET["s"] > 0 && !foraLimit()) { //passem tots a suplents
	$s = $_GET["s"];
	$c = $_GET["c"];
	if (($s == 1) && ($userId2 == $c ||  $userType != "Registered")) {
		totsSuplents($c, $jornadaActual);
/*RAB*/	$msg2 = EscriuLOG ('TotsSuplen', 0, 0, $infoCon["nom"], $infoCon2["nom"]);
	}
}

if (isset($_GET["estrat"])  && !foraLimit()) {
	canviarEstrategia($infoCon["id"], $jornadaActual, $_GET["estrat"]);
/*RAB*/	$msg2 = EscriuLOG ('Estratègia', $j, $v, $infoCon["nom"], $infoCon2["nom"]);
}
//per si s'han fet canvis
$infoCon = getInfoConcursant($userId);

//desglosem la estrategia
$estrategia = desglosaEstrategia($infoCon["estrategia"]);

/*21/5/2016*/ }
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
 <link type="text/css" href="estil.css?<?php echo date('Y-m-d H:i:s'); ?>" rel="stylesheet" /> 
<script language="javascript">
function obrirFinestra(url) {
	window.open(url,"jugadors_posicio","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=yes, width=600, height=400, fullscreen=no");
}
function canviaStrategia(c) {
window.location = "fer_equip.php?estrat=" + document.getElementById("estrategia").value + "&c=" + c;
}

function FinestraLog(id1, id2, j) {
window.open("guardar_alineacio.php?id1=" + id1 + "&id2=" + id2 + "&j=" + j, "log", 'left=250,top=250,width=300,height=400,toolbar=0');
}



</script>
</head>
<body>
<b><P><?php if ($escopa > 0)  echo "<font size=\"2\" color=\"#ff0000\">AQUESTA JORNADA HI HA COPA (Per si vols analitzar el rival)<br/></font>";?></P></b>
<?php
if ($userType == "Super Administrator") { //pot canviar a qui vullga
IF ($jornadaActual > 0) {
$llistaCon = getTaula("concursants", "usuari");
echo "|";
for ($i = 0; $i < sizeof($llistaCon); $i++) {
	if ($userId != $llistaCon[$i]["id"]) {
?>
	<a href="fer_equip.php?c=<?= $llistaCon[$i]["id"] ?>"><?= utf8_encode($llistaCon[$i]["usuari"]); ?></a>|
<?php
	}else{
?>
	<?= utf8_encode($llistaCon[$i]["usuari"]); ?>|
<?php
	}
}
}//FI JORNADA = 0
}//fi canviar a qui vullga
?>

<?php 
	$jugadors = getJugadorsPerPersona($userId, $jornadaActual);
	
	$titulars = getTotalJugs($userId, $jornadaActual);
$jornadaActual = getJornadaActual();
$jornadaLFP = $jornadaActual + 4;
$enfrontLFP = getEnfrontamentsLFP($jornadaLFP);
?>
<table border="1" class="moduletable5" align="center" width="100%">
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

<?php for ($it = 0 ; $it < 10 ; $it++) { ?>
	<td width="10%" height="40"><center>
	<?php 
		echo "<img align=center img src='LFP_mini/".$enfrontLFP[$it]["idEquip2"].".png' border='0'>"; 		
	?>
	</center>
	</td>
<?php } ?>	
</tr>

</table>
<table border="1" class="moduletable" align="center" width="100%">
<th><?= utf8_encode($infoCon["nom_equip"]); ?></th>
<tr>
	<td><div align="center">
		** Estratègia: 
		<select id="estrategia" name="estrategia" onChange="javascript:canviaStrategia(<?= $userId ?>)">
			<option value="343" <?php if ($infoCon["estrategia"] == 343) echo "selected"; ?>>3-4-3</option>
			<option value="442" <?php if ($infoCon["estrategia"] == 442) echo "selected"; ?>>4-4-2</option>
			<option value="631" <?php if ($infoCon["estrategia"] == 631) echo "selected"; ?>>6-3-1</option>
			<option value="541" <?php if ($infoCon["estrategia"] == 541) echo "selected"; ?>>5-4-1</option>
			<option value="451" <?php if ($infoCon["estrategia"] == 451) echo "selected"; ?>>4-5-1</option>
			<option value="361" <?php if ($infoCon["estrategia"] == 361) echo "selected"; ?>>3-6-1</option>
			<option value="433" <?php if ($infoCon["estrategia"] == 433) echo "selected"; ?>>4-3-3</option>
			<option value="532" <?php if ($infoCon["estrategia"] == 532) echo "selected"; ?>>5-3-2</option>
			<option value="352" <?php if ($infoCon["estrategia"] == 352) echo "selected"; ?>>3-5-2</option>

		</select>
		</div>
	</td>
</tr>
</table>

<?php if ($titulars[0] == 0 && ($userType == "Super Administrator"))  { ?>
<table border="1" class="moduletable" align="center" width="100%">
<tr>
	<td><div align="left"><a href="replica_alineacio.php?c=<?= $userId ?>">REPETIR ALINEACIO DE LA JORNADA ANTERIOR</a></div></td>
</tr>
</table>
<?php } ?>

<?php if ($titulars[0] >= 1)  { ?>
<table border="1" class="moduletable" align="center" width="100%">
<tr>
	<td><div align="left"><a href="fer_equip.php?s=1&c=<?= $userId ?>">PASSAR TOTS ELS JUGADORS A SUPLENTS</a></div></td>
</tr>
</table>
<?php } ?>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="4">Equip titular (<?php echo $titulars[0] ?>)</th>
<tr><td>Porter</td><td>Defenses</td><td>Mijos</td><td>Davanters</td></tr>
<tr>
	<td width="25%">
	<?php 
	$porters = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 1 && $jugadors[$i]["seleccionat"] == 1) {
			$porters++;
	?>
		<a href="fer_equip.php?v=0&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	$defenses = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 2 && $jugadors[$i]["seleccionat"] == 1) {
			$defenses++;
	?>
		<a href="fer_equip.php?v=0&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	$mijos = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 3 && $jugadors[$i]["seleccionat"] == 1) {
			$mijos++;
	?>
		<a href="fer_equip.php?v=0&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?><br>
	<?php
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	$davanters = 0;
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 4 && $jugadors[$i]["seleccionat"] == 1) {
			$davanters++;
	?>
		<a href="fer_equip.php?v=0&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?><br>
	<?php
		}
	}
	?>
	</td>
</tr>
</table>

<table border="1" class="moduletable" align="center" width="100%">
<th colspan="4">Suplents (<?php echo $titulars[1] ?>)</th>
<tr><td>Porter</td><td>Defenses</td><td>Mijos</td><td>Davanters</td></tr>
<tr>
	<td width="25%">
	<?php 
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 1 && $jugadors[$i]["seleccionat"] == 0) {
			if ($porters < 1) {
	?>
		<a href="fer_equip.php?v=1&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?> 
		<?php if ($jornadaActual == $JORNADA_BASE || $userType == "Administrator" || $userType == "Super Administrator") { ?>
		[<a href="fer_equip.php?c=<?= $userId ?>&op=anularJugador&id=<?= $jugadors[$i]["id"] ?>">X</a>]
		<?php } ?>
		<br>
	<?php
			}
			else {
				echo utf8_encode($jugadors[$i]["nom"]) . " (" . $jugadors[$i]["sigles"] . " " . $jugadors[$i]["valor"] . ")";
				if ($jugadors[$i]["ecomunitari"]) echo "*";
				if ($jornadaActual == $JORNADA_BASE || $userType == "Super Administrator") {
					echo " [<a href=\"fer_equip.php?c=$userId&op=anularJugador&id=" . $jugadors[$i]["id"] . "\">X</a>]";
				} 
				echo "<br>";
			}
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 2 && $jugadors[$i]["seleccionat"] == 0) {
			if ($defenses < $estrategia[2]) {
	?>
		<a href="fer_equip.php?v=1&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?>
		<?php if ($jornadaActual == $JORNADA_BASE || $userType == "Administrator" || $userType == "Super Administrator") { ?>
		[<a href="fer_equip.php?c=<?= $userId ?>&op=anularJugador&id=<?= $jugadors[$i]["id"] ?>">X</a>]
		<?php } ?>
		<br>
	<?php
			}
			else {
				echo utf8_encode($jugadors[$i]["nom"]) . " (" . $jugadors[$i]["sigles"] . " " . $jugadors[$i]["valor"] . ")";
				if ($jugadors[$i]["ecomunitari"]) echo "*";
				if ($jornadaActual == $JORNADA_BASE || $userType == "Administrator" || $userType == "Super Administrator") {
					echo " [<a href=\"fer_equip.php?c=$userId&op=anularJugador&id=" . $jugadors[$i]["id"] . "\">X</a>]";
				}
				echo "<br>";
			}	
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 3 && $jugadors[$i]["seleccionat"] == 0) {
			if ($mijos < $estrategia[3]) {
	?>
		<a href="fer_equip.php?v=1&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?>
		<?php if ($jornadaActual == $JORNADA_BASE || $userType == "Administrator" || $userType == "Super Administrator") { ?>
		[<a href="fer_equip.php?c=<?= $userId ?>&op=anularJugador&id=<?= $jugadors[$i]["id"] ?>">X</a>]
		<?php } ?>
		<br>
	<?php
			}
			else {
				echo utf8_encode($jugadors[$i]["nom"]) . " (" . $jugadors[$i]["sigles"] . " " . $jugadors[$i]["valor"] . ")";
				if ($jugadors[$i]["ecomunitari"]) echo "*";
				if ($jornadaActual == $JORNADA_BASE || $userType == "Administrator" || $userType == "Super Administrator") {
					echo " [<a href=\"fer_equip.php?c=$userId&op=anularJugador&id=" . $jugadors[$i]["id"] . "\">X</a>]";
				}
				echo "<br>";
			}
		}
	}
	?>
	</td>
	<td width="25%">
	<?php 
	for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
		if ($jugadors[$i]["posicio"] == 4 && $jugadors[$i]["seleccionat"] == 0) {
			if ($davanters < $estrategia[4]) {
	?>
		<a href="fer_equip.php?v=1&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= utf8_encode($jugadors[$i]["nom"]); ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?>
		<?php if ($jornadaActual == $JORNADA_BASE || $userType == "Administrator" || $userType == "Super Administrator") { ?>
		[<a href="fer_equip.php?c=<?= $userId ?>&op=anularJugador&id=<?= $jugadors[$i]["id"] ?>">X</a>]
		<?php } ?>
		<br>
	<?php
			}
			else {
				echo utf8_encode($jugadors[$i]["nom"]) . " (" . $jugadors[$i]["sigles"] . " " . $jugadors[$i]["valor"] . ")";
				if ($jugadors[$i]["ecomunitari"]) echo "*";
				if ($jornadaActual == $JORNADA_BASE || $userType == "Administrator" || $userType == "Super Administrator") {
					echo " [<a href=\"fer_equip.php?c=$userId&op=anularJugador&id=" . $jugadors[$i]["id"] . "\">X</a>]";
				}
				echo "<br>";
			}
		}
	}
	?>
	</td>
</tr>
</table>

<?php if ($jornadaActual == $JORNADA_BASE || $userType == "Administrator" || $userType == "Super Administrator")  { ?>
<table border="1" class="moduletable" align="center" width="100%">
<th colspan="4">Sel·leccionar Jugadors</th>
<tr>
	<td><div align="center"><a href="elegir_jugador.php?c=<?= $userId ?>&pos=1">Porter</a></div></td>
	<td><div align="center"><a href="elegir_jugador.php?c=<?= $userId ?>&pos=2">Defenses</a></div></td>
	<td><div align="center"><a href="elegir_jugador.php?c=<?= $userId ?>&pos=3">Mijos</a></div></td>
	<td><div align="center"><a href="elegir_jugador.php?c=<?= $userId ?>&pos=4">Davanters</a></div></td>
</tr>
</table>

<?php } ?>
<b>Presupost Gastat: <?php $pre=getPresupostGastat($userId);echo $pre; ?>, restant: <?= $PRESUPOST-$pre ?>.</b>
<?php if (foraLimit()) $msg1 = "S'HA TANCAT EL PLAÇ PER A FER CANVIS AQUESTA JORNADA!!!"; ?>
<br><font color="#FF0000" size="4"><b><?= $msg1 ?></b></font>

<br>
<?php
putenv('TZ=Europe/Madrid');  //Per a obtindre l'hora espanyola
$data = strftime("%d/%m/%Y", time());
$hora = date("-H:i",time());
?>
<font face="Courier New"><b>Hora actu.: <?= $data . $hora ?></b></font>
<br>
<font color="#FF0000" face="Courier New"><b>Data límit: <?= getValorConfiguracio("data_limit") ?></b></font>
<? //date("H:i") ?>
<br><br>
<div class="back_button"><a href="login1.php?tancar=si">Tancar</a></div> &nbsp;&nbsp;&nbsp;
<!-- Desactive el botó
<div class="back_button"><a href="javascript:FinestraLog(<?=$userId ?>,<?=$userId2 ?>,<?=$jornadaActual ?>)">Guardar</a></div></td>-->


</body>
</html>
