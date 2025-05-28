<?php
/************************************************
*	File: 	classificacio.php					*
*	Desc: 	versió WAP per a fer els canvis		*
*	Author:	Jose Gargallo 						*
************************************************/

// sense cookies en WAP
ini_set('session.use_cookies', 0);

// session.use_trans_sid will break our XML as it appends URL parameters with & instead of &amp;
// - since session.use_trans_sid cannot be disabled in a script, set url_rewriter.tags instead
ini_set('url_rewriter.tags', '');

// traguem el id de la sessio
if (isset($_REQUEST[ 'ses' ])) session_id($_REQUEST[ 'ses' ]);

// iniciem la sessio
session_name('actesoficialswap');
@session_start();

//includes de tota la vida
include("config.php");
include("funcions.php");

// Inicialitzem les dades de la sessio
if (! isset($_SESSION[ 'userid' ]))
  $_SESSION[ 'userid' ] = '';

//capsalera per al wml
header('Content-type: text/vnd.wap.wml');
echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";

?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
<card title="LFE" newcontext="true">
<p>
<?php

// Default mode: Display HOME page

$mode = '';
$errmsg = '';

// Ix si s'ha fet el "logout"
if (isset($_REQUEST[ 'logout' ]))
  $_SESSION[ 'userid' ] = '';

// Fa el login
if (isset($_REQUEST[ 'user' ]) && isset($_REQUEST[ 'pass' ]))
  if ($userId = concursantValid($_REQUEST[ 'user' ], $_REQUEST[ 'pass' ]))
  	$_SESSION[ 'userid' ] = $userId;
  else
	$errmsg = 'Dades incorrectes';

// mostrem el formulari de login si no ha entrat
if ($_SESSION[ 'userid' ] == '')
	$mode = 'login';
else
	$mode = 'display';

echo $_REQUEST["user"];

// LOGIN: Display login form
if ($mode == 'login')
{
	$uservalue = '';
	if (isset($_REQUEST[ 'user' ]))	$uservalue = $_REQUEST[ 'user' ];

	// Display error message
	if ($errmsg != '') echo "<em>$errmsg</em><br/>"; ?>

	Usuari: <input type="text" name="user" emptyok="false" title="User" value="<?php echo htmlspecialchars($uservalue); ?>"/><br/>
	Clau: <input type="password" name="pass" emptyok="false" title="Password" value=""/><br/>

	<anchor>
		Entrar
		<go href="<?php echo $_SERVER[ 'PHP_SELF' ]; ?>" method="post">
		<postfield name="user" value="$user"/>
		<postfield name="pass" value="$pass"/>
		<postfield name="ses" value="<?php echo session_id(); ?>"/>
		</go>
	</anchor>

<?php
} else {
	// DISPLAY: mostra l'equip
	$userId = $_SESSION[ 'userid' ];
	$jornadaActual = getJornadaActual();
	$infoCon = getInfoConcursant($userId);

	if (isset($_REQUEST["j"]) && $_REQUEST["j"] > 0 && !foraLimit()) { //hi ha canvi de jugador
		$v = $_GET["v"];
		$j = $_GET["j"];
		if ($v == 0 || $v == 1) {
			$errmsg = canviaSeleccioJugador($userId, $j, $jornadaActual, $v, $infoCon["estrategia"]);
		}
	}

	if (isset($_REQUEST["estrategia"])  && !foraLimit()) {
		canviarEstrategia($userId, $jornadaActual, $_REQUEST["estrategia"]);
	}
	//per si s'han fet canvis
	$infoCon = getInfoConcursant($userId);

	//desglosem la estrategia
	$estrategia = desglosaEstrategia($infoCon["estrategia"]);

	//traem els jugadors
	$jugadors = getJugadorsPerPersona($userId, $jornadaActual);

?>
<strong>Jornada <?= $jornadaActual ?></strong>
<br/>
Estratègia:
<select id="estrategia" name="estrategia">
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
<anchor title="canviaEstrat">Canviar Estrat.
<go href="<?php echo $_SERVER[ 'PHP_SELF' ]; ?>?ses=<?=urlencode(session_id())?>" method="post">
<postfield name="estrategia" value="$(estrategia)"/>
</go>
</anchor>
<br/>
<br/>
<strong>Titulars: </strong>
<?php
$porters = 0;
for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
	if ($jugadors[$i]["posicio"] == 1 && $jugadors[$i]["seleccionat"] == 1) {
		$porters++;
	?>
		<a href="fer_equip.php?v=0&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= $jugadors[$i]["nom"] ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?><br/>
<?php
	}
}
$defenses = 0;
for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
	if ($jugadors[$i]["posicio"] == 2 && $jugadors[$i]["seleccionat"] == 1) {
		$defenses++;
?>
	<a href="fer_equip.php?v=0&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= $jugadors[$i]["nom"] ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?><br/>
<?php
	}
}
$mijos = 0;
for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
	if ($jugadors[$i]["posicio"] == 3 && $jugadors[$i]["seleccionat"] == 1) {
		$mijos++;
?>
	<a href="fer_equip.php?v=0&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= $jugadors[$i]["nom"] ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?><br/>
<?php
	}
}
$davanters = 0;
for ($i = 0 ; $i < sizeof($jugadors) ; $i++) {
	if ($jugadors[$i]["posicio"] == 4 && $jugadors[$i]["seleccionat"] == 1) {
		$davanters++;
?>
	<a href="fer_equip.php?v=0&c=<?= $infoCon["id"] ?>&j=<?= $jugadors[$i]["id"] ?>&jo=<?= $jornadaActual ?>"><?= $jugadors[$i]["nom"] ?> (<?= $jugadors[$i]["sigles"] ?> <?= $jugadors[$i]["valor"] ?>)</a> <?php if ($jugadors[$i]["ecomunitari"]) echo "*"; ?><br/>
<?php
	}
}
?>
<br/>
<strong>Suplents: </strong>

<br/>
<br/>
<em><?= $errmsg ?></em>
<br/>
<a href="<?=$_SERVER[ 'PHP_SELF' ]?>?ses=<?=urlencode(session_id())?>&amp;logout=1">Eixir</a>
<?php
}
?>
</p>
</card>
</wml>