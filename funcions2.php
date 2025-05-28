<?php
/************************************************
*	File: 	funcions2.php						*
*	Desc: 	més funcions						*
*	Author:	Rubén Aparici 						*
************************************************/

include("config.php");
include("funcions.php");


function getJugadorsFiltrats($equip,$preu,$pos)  
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, j.posicio, j.valor, j.ecomunitari, e.sigles, SUM(p.punts) as punts";
	$sql .= " FROM equips e, jugadors j LEFT JOIN punts_jornada p ON j.id = p.idJugador";
	$sql .= " WHERE j.equip = e.id";
	if ($equip != 99) $sql .= " AND j.equip = $equip"; 
	if ($preu != 99) $sql .= " AND j.valor <= $preu";
	if ($pos != 99) $sql .= " AND j.posicio = $pos";
	$sql .= " GROUP BY j.id";
	$sql .= " ORDER BY punts desc, j.valor desc, j.posicio asc, j.nom asc";	
	$sql .= " LIMIT 1000 ";	
	$result = mysql_query($sql, $link);

	$taula = null;
	if (mysql_num_rows($result)) {
			$i = 0;
			while($row = mysql_fetch_array($result)) {
					$taula[$i] = $row;
					$i++;
			}
	}
	return $taula;
}


/*
function getJugadorsFiltrats($equip,$preu,$pos)  
{
	$link = conectar();

	$sql = "SELECT j.id, j.nom, j.posicio, j.valor, j.ecomunitari";
	$sql .= ", e.sigles";
	$sql .= ", SUM(p.punts) as punts";
	$sql .= " FROM jugadors j, punts_jornada p, equips e";
	$sql .= " WHERE j.id = p.idJugador";
	$sql .= " AND j.equip = e.id";
	if ($equip != 99) $sql .= " AND j.equip = $equip";
	if ($preu != 99) $sql .= " AND j.valor <= $preu";
	if ($pos != 99) $sql .= " AND j.posicio = $pos";
	$sql .= " GROUP BY j.id";
	$sql .= " ORDER BY punts desc, j.valor asc, j.posicio asc, j.nom asc";	
	$sql .= " LIMIT 100 ";	
	
	$result = mysql_query($sql, $link);
	
	$taula = NULL;

	if ($result != NULL) {
		$i=0;
		while($row = mysql_fetch_array($result)) {
			$taula[$i] = $row;
			$i++;
		}
	}
	return $taula;
}
*/


function getPreus(){
	
	$link = conectar();
	
	$sql = "SELECT distinct valor from jugadors order by valor asc";
	$result = mysql_query($sql, $link);
	
	$taula = null;	
	
		if (mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			$i=0;
			do{
				$taula[$i] = $row; 
				$i++;
			} while($row = mysql_fetch_array($result)) ;
		}	
	return $taula;
}

	
function getPuntsJug($j){
	$link = conectar();	
	$sql = "SELECT jornada, punts FROM punts_jornada WHERE idJugador = $j ";
	
	$result = mysql_query($sql, $link);
	
	$taula = null;	
	
		if (mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			$i=0;
			do{
				$taula[$i] = $row; 
				$i++;
			} while($row = mysql_fetch_array($result)) ;
		}	
	return $taula;	
	
}

//Torna la posició d'un jugador
function consultarPosicio($jId){
	$link = conectar();

	$sql = "SELECT posicio from jugadors where id = $jId";
	
	$result  = mysql_query($sql, $link);
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		return $row["posicio"];
	}
	return null;
}


function getNomComplet($usuari) {

	$link = conectar();

	$sql = "SELECT name from mos_users WHERE username = '$usuari'";

	$result = mysql_query($sql, $link);

	$row = mysql_fetch_array($result);

	return $row;

}

//comproba si l'alineació que s'intenta penjar és correcta
function comprobarAlineacio($contador, $titulars){
	
	if ($titulars > 11) return "No pots alinear més de 11 jugadors!";

	else if ($contador[1] < 1) return "No has alineat cap porter!";
	else if ($contador[1] > 1) return "No pots alinear més d'un porter!";
	else if ($contador[2] < 3) return "Has d'alinear un mínim de 3 defenses!";
	else if ($contador[2] > 6) return "No pots alinear més de 6 defenses!";
	else if ($contador[3] < 3) return "Has d'alinear un mínim de 3 mitjos!";
	else if ($contador[3] > 6) return "No pots alinear més de 6 mitjos!";
	else if ($contador[4] < 1) return "No has alineat cap davanter!";
	else if ($contador[4] > 3) return "No pots alinear més de 3 davanters!";
	
	else if ($titulars < 11) return "ATENCIÓ: Has alineat menys de 11 jugadors!";
	
	return "ALINEACIO GUARDADA CORRECTAMENT";
}

function updateAlineacio2($c, $jId, $jornada, $sel){

	$link = conectar();	
	
	$sql = "UPDATE jugadors_elegits ";	
	$sql .= "SET seleccionat = $sel ";	
	$sql .= "WHERE idConcursant = $c ";	
	$sql .= "AND jornada = $jornada ";	
	$sql .= "AND idJugador = $jId ";	
	
	$result = mysql_query($sql, $link);	
	
}

function getEnfrontamentsLFP($j){
	$link = conectar();	
	$sql = "SELECT idEquip1, idEquip2 FROM enfrontaments_lfp WHERE jornada = $j ";
	
	$result = mysql_query($sql, $link);
	
	$taula = null;	
	
		if (mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			$i=0;
			do{
				$taula[$i] = $row; 
				$i++;
			} while($row = mysql_fetch_array($result)) ;
		}	
	return $taula;	
}

//afegix un enfrontament LFP
function addEnfrontamentLFP($e1, $e2, $jornada) {
	$link = conectar();
	
	$sql = "INSERT INTO enfrontaments_lfp VALUES ($e1,$e2,$jornada)";
	$result = mysql_query($sql, $link);
}

//si la jornada LFP te enfrontaments
function jorLFPAmbEnfrontaments($jornada) {
	$link = conectar();
	
	$sql = "SELECT * FROM enfrontaments_lfp WHERE jornada = $jornada";
	$result = mysql_query($sql, $link);
	return mysql_num_rows($result);
}

function getEnfrontamentsLFP_jug_eq($e){
	$link = conectar();	
	$sql = "SELECT idEquip1, idEquip2 FROM enfrontaments_lfp WHERE idEquip1 = $e or idEquip2 = $e order by jornada";
	
	$result = mysql_query($sql, $link);
	
	$taula = null;	
	
		if (mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			
			$i=0;
			do{
				$taula[$i] = $row; 
				$i++;
			} while($row = mysql_fetch_array($result)) ;
		}	
	
	return $taula;	
}


function updateBloquejats() {

	$link = conectar();
	
	$sql = "UPDATE jugadors SET ecomunitari = 1 ";
	$sql .= "WHERE id IN ";
	$sql .= "(SELECT je.idJugador ";
	$sql .= "FROM  jugadors_elegits je  ";
	$sql .= "WHERE  je.jornada = 0 ";
	$sql .= "GROUP BY je.idJugador ";
	$sql .= "HAVING COUNT(je.idConcursant) >= 5) ";
	
	$result = mysql_query($sql, $link);	
}

//Repetir alineació jornada anterior
function updateAlineacio($con, $jornada) {

	$link = conectar();	
	
	$jorAnt = $jornada - 1;	
	
	$sql = "UPDATE jugadors_elegits a, ";	
	$sql .= "(SELECT * FROM jugadors_elegits b ";	
	$sql .= "WHERE b.idConcursant = $con ";	
	$sql .= "AND b.jornada = $jorAnt ";	
	$sql .= "AND b.seleccionat = 1) c ";	
	$sql .= "SET a.seleccionat = 1 ";	
	$sql .= "WHERE a.idConcursant = c.idConcursant ";	
	$sql .= "AND a.jornada = $jornada ";	
	$sql .= "AND a.idJugador = c.idJugador ";	
	
	$result = mysql_query($sql, $link);	
}

/*update jugadors_elegits a,(SELECT * FROM jugadors_elegits bwhere b.idConcursant = 7and b.jornada = 22and b.seleccionat = 1) cset a.seleccionat = 1where a.idConcursant = c.idConcursantand a.jornada = 23and a.idJugador = c. idJugador*/
function getTotalAnys(){
	
	$link = conectar();
	
	$sql = "SELECT distinct any from palmares order by any desc";
	$result = mysql_query($sql, $link);
	
	$taula = null;	
	
		if (mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			$i=0;
			do{
				$taula[$i] = $row; 
				$i++;
			} while($row = mysql_fetch_array($result)) ;
		}	
	return $taula;
}



function getTitolsHistoria($any){

	$link = conectar();
	
	$sql = "SELECT p.any, p.competicio,p.idjug,p.comentaris from palmares p where p.any=$any";
	
	$result = mysql_query($sql, $link);
	$taula = null;	
	
		if (mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			$i=0;
			do{
				$taula[$i] = $row; 
				$i++;
			} while($row = mysql_fetch_array($result)) ;
		}	
	return $taula;
}


function getPalmares($idjug){
	
	$link = conectar();
	
	$sql = "SELECT any,competicio from palmares where idjug = '$idjug' order by any";
	$result = mysql_query($sql, $link);
	$taula = null;	
	
		if (mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			$i=0;
			do{
				$taula[$i] = $row; 
				$i++;
			} while($row = mysql_fetch_array($result)) ;
		}	
	return $taula;
		
}



/**
 * Reemplaza todos los acentos por sus equivalentes sin ellos
 *
 * @param $string
 *  string la cadena a sanear
 *
 * @return $string
 *  string saneada
 */
function quitarAcentos($string)
{
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "<", ";", ",", ":",
             ".", " "),
        '',
        $string
    );
 
    return $string;
}
	

//canvia per a selecionar o no un jugador
function canviaSeleccioJugador($c, $j, $jo, $v, $estrat) {
	global $NUM_ECOMUNITARIS_JUGANT;

	$link = conectar();
	$ok = true;
	if ($v == 1) { //passa a ser titular
		$infoJug = getInfoJugador($j);
		if ($infoJug["ecomunitari"] == 1) {
			$sql = "SELECT * FROM jugadors j, jugadors_elegits e WHERE j.id = e.idJugador AND e.idConcursant = $c AND e.jornada = $jo AND j.ecomunitari = 1 AND e.seleccionat = 1";
			$result = mysql_query($sql, $link);
			if (mysql_num_rows($result) >= $NUM_ECOMUNITARIS_JUGANT) {
				$ok = false;
				return "Sols pots tindre $NUM_ECOMUNITARIS_JUGANT jugadors extracomunitaris en el 11 titular";
			}
		}	
		
		$estrategia = desglosaEstrategia($estrat);
		$sql = "SELECT * FROM jugadors j, jugadors_elegits e WHERE j.id = e.idJugador AND e.idConcursant = $c AND e.jornada = $jo AND j.posicio = " . $infoJug["posicio"] . " AND e.seleccionat = 1";
		$result = mysql_query($sql, $link);
		if (mysql_num_rows($result) >= $estrategia[$infoJug["posicio"]]) {
			$ok = false;
			return "Amb l'estrategia elegida no pots disposar de més jugadors en aquesta posició.";
		}
	
		$sql = "SELECT count(j.id)";
		$sql .= " FROM jugadors_elegits je, jugadors j, equips e";
		$sql .= " WHERE je.idJugador = j.id AND j.equip = e.id";
		$sql .= " AND je.jornada = " . $jo . " AND seleccionat = 1 AND je.idConcursant = " . $c;
		$sql .= " ORDER BY posicio";
		$result = mysql_query($sql, $link);
		$titulars = mysql_fetch_array($result);
		if ($titulars[0] >= 11){
			$ok = false;
			return "Ja tens 11 jugadors alineats, aspabilat!";
		}

	}

	if ($ok) {
		$sql = "UPDATE jugadors_elegits SET seleccionat = $v WHERE idConcursant = $c AND idJugador = $j AND jornada = $jo";
		$result  = mysql_query($sql, $link);
	}
}

	
	
//Torna els jugadors de un determinat concursant i jornada
function getTotalJugs($persona, $jornada)
{
	$link = conectar();
	$sql = "SELECT count(j.id)";
	$sql .= " FROM jugadors_elegits je, jugadors j, equips e";
	$sql .= " WHERE je.idJugador = j.id AND j.equip = e.id";
	$sql .= " AND je.jornada = " . $jornada . " AND seleccionat = 1 AND je.idConcursant = " . $persona;
	$sql .= " ORDER BY posicio";

	$result = mysql_query($sql, $link);
	
	$row = mysql_fetch_array($result);
	
	
	 $sql1 = "SELECT count(j.id)";
	 $sql1 .= " FROM jugadors_elegits je, jugadors j, equips e";
	 $sql1 .= " WHERE je.idJugador = j.id AND j.equip = e.id";
	 $sql1 .= " AND je.jornada = " . $jornada . " AND seleccionat = 0 AND je.idConcursant = " . $persona;
	 $sql1 .= " ORDER BY posicio";

	 $result1 = mysql_query($sql1, $link);
	
	 $row1 = mysql_fetch_array($result1);
	
	 $taula[0] = $row[0];
	 $taula[1] = $row1[0];
	
		
    return $taula;
}


/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
./·':-                                           ./·':-
./·':-           getEnfrontamentsCopa            ./·':-
./·':-                                           ./·':-
./·':- Obté els enfrontaments per ronda en copa  ./·':-
./·':-                                           ./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·'*/

function getEnfrontamentsCopa($ronda) {
	$link = conectar();
	
	$sql = "SELECT idJugador1, idJugador2, jornada,comentaris FROM copa WHERE ronda = '$ronda' and idJugador1>0 order by idJugador1, idJugador2";
	$result = mysql_query($sql, $link);

	$taula = null;
	if (mysql_num_rows($result)) {

			$row = mysql_fetch_array($result);
			
			$jornada = $row["jornada"];
			$comentaris = $row["comentaris"];						
			$i = 0;
			
			do{
					$info1 = getInfoConcursant($row["idJugador1"]);
					$info2 = getInfoConcursant($row["idJugador2"]);
					$taula[$i][0]["id"] = $info1["id"];
					$taula[$i][1]["id"] = $info2["id"];
					$taula[$i][0]["nom"] = $info1["nom"];
					$taula[$i][1]["nom"] = $info2["nom"];
		            $punts1 = getPuntsPerJornadaYConcursant($jornada, $row["idJugador1"]);
		            $punts2 = getPuntsPerJornadaYConcursant($jornada, $row["idJugador2"]);
					$taula[$i][0]["punts"] = $punts1["punts"] ; 
					$taula[$i][1]["punts"] = $punts2["punts"] ; 
					$i++;

			} while($row = mysql_fetch_array($result)) ;
	}
	$taula[0][0]["jornada"] = $jornada;
	$taula[0][0]["comentaris"] = $comentaris;
	
	return $taula;
}


function EsJornadaCopa($jornada){
	$link = conectar();

	$sql = "SELECT count(idJugador1) from copa where jornada=$jornada";
	
	$result = mysql_query($sql, $link);
	$row = mysql_fetch_array($result);
	
	return $row[0];
	
}


//anyadix un enfrontament
function addEnfrontamentCopa($c1, $c2, $jornada) {
	$link = conectar();

	$sql = "SELECT ronda,comentaris from copa where idJugador1=0 and jornada=$jornada";
	$result = mysql_query($sql, $link);
	$ronda = mysql_fetch_array($result);
	
	$sql1 = "INSERT INTO copa VALUES ($c1,$c2,$jornada,'$ronda[0]', '$ronda[1]')";
	$result1 = mysql_query($sql1, $link);
	
	/*

	$sql = "SELECT idJugador1,idJugador2,ronda from copa where jornada = $j";
	$result = mysql_query($sql, $link);
	
	
		if ($row = mysql_fetch_array($result)){	
				$sql = "UPDATE copa set idJugador1 = $c1, idJugador2 = $c2 WHERE jornada = $j";	
				$result = mysql_query($sql, $link);
		}
		else{
			return "Primer has d'asignar ronda a jornada corresponent.";
		}
	
	return "Enfrontaments assinats correctament!";
	*/
}

//si la jornada te enfrontaments
function jornadaAmbEnfrontamentsCopa($jornada) {
	$link = conectar();
	
	$sql = "SELECT * FROM copa WHERE jornada = $jornada";
	$result = mysql_query($sql, $link);
	return mysql_num_rows($result);
}


/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
./·':-                                           ./·':-
./·':-           asignaJornadaCopa               ./·':-
./·':-                                           ./·':-
./·':-   Asigna una jornada a una ronda de copa  ./·':-
./·':-                                           ./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·'*/

// $r -> ronda
// $j -> jornada
function asignaJornadaCopa($r, $j) 
{
	$link = conectar();
	
	$sql = "INSERT INTO copa VALUES (0,0,$j,'$r', ' ')";
	$result = mysql_query($sql, $link);
	
	
	/*
	$sql = "SELECT idJugador1,idJugador2 from copa where jornada = $j";
	$result = mysql_query($sql, $link);
	
	
		if ($row = mysql_fetch_array($result)){	
				$sql = "INSERT copa set ronda = '$r' WHERE jornada = $j";	
				$result = mysql_query($sql, $link);
		}
		else{
			return "Primer has de definir els enfrontaments de la jornada corresponent.";
		}
	
	return "Ronda $r asignada a la jornada $j";
	*/
}


function consultaJornadesCopa() 
{
	$link = conectar();
	
	$sql = "SELECT distinct jornada,ronda from copa";
	$result = mysql_query($sql, $link);
	$taula = null;	
	
		if (mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			$i=0;
			do{
				$taula[$i] = $row; 
				$i++;
			} while($row = mysql_fetch_array($result)) ;
		}	
	return $taula;
}

function consultaJornadaCopa($r) 
{
	$link = conectar();
	
	$sql = "SELECT jornada FROM copa WHERE idJugador1 = 0 AND ronda = '$r'";
	$result = mysql_query($sql, $link);
	$row = mysql_fetch_array($result);
	
	return $row[0];
}

function consultaComentariCopa($r) 
{
	$link = conectar();
	
	$sql = "SELECT comentaris FROM copa WHERE idJugador1 = 0 AND ronda = '$r'";
	$result = mysql_query($sql, $link);
	$row = mysql_fetch_array($result);
	
	return $row[0];
}



/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
./·':-                                           ./·':-
./·':-          getClassHistòriques              ./·':-
./·':-                                           ./·':-
./·':-   Consulta els punts dels participants    ./·':-
./·':-   en anys anteriors. També calcula        ./·':-
./·':-   una classificació total històrica.      ./·':-
./·':-                                           ./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·'*/

//si volem nom_equip, millor consultar-lo de la tabla ja existent de noms d'equips... o en lloc d'això, mostrar alguna altra dada
// com per exemple: Número de participacions (o temporades jugades) i any de debut + posició?
// function getClassHistoriques($a, $t)
// {
// global $NUM_TEMPORADES,$TEMP_ACTUAL;
// // LA EXTRA TAL VOLTA DEURIA CALCULAR RATIOS, NO PUNTS TOTALS... DIVIDIR PUNTS DEL CONCURSANT ENTRE PUNTS DEL CAMPIÓ (MAXIMS)
// // JA QUE ELS ANYS PRIMERS SE FEIEN MENYS PUNTS PERQUÈ JUGAVEM MENYS JORNADES:

// // punts_concursant / punts_campio


// /* millor calcular la importancia de cada lliga, no? 

// millor temporada = temporada amb més punts

// ratio temporada 1 =  temporada 1 / millor temporada
// ratio temporada 2 =  temporada 2 / millor temporada
// ratio temporada 3 =  temporada 3 / millor temporada
// ratio temporada 4 =  temporada 4 / millor temporada
// ratio temporada 5 =  temporada 5 / millor temporada

// I per tant, els punts de cada jugador: (punts-temp1 * ratio1 + punts2*ratio2 + punts3*temp3) / 3 * 5
			
// */
	// $link = conectar();
	
	// $temp = $TEMP_ACTUAL;
	// if ($a == 'HistExtra')	$temp--;
	
	// if ($a == 'Històrica' || $a == 'HistExtra') {//HISTÒRICA / HISTÒRICA ETRAPOLADA: ignorarem l'any en la consulta SELECT		
		
	// /*1*/$sql = "SELECT concursant,punts,puntaverage from classificacions where tipo_class='$t' and any <= $temp ORDER BY concursant"; 
	// /*1*/
	// /*1*/$result = mysql_query($sql, $link);
	// /*1*/
	// /*1*/$taula = null;
	// /*1*/if (mysql_num_rows($result)) {
	// /*1*/	$i = 0;
	        // $row = mysql_fetch_array($result);
	// /*1*/	$nom = $row[0];
	// /*1*/	$punts = 0;
	// /*1*/	$puntaverage = 0;
			// $participacions = 0;
			// $redondeo = 0;
	// /*1*/	do{
	// /*1*/		if ($row[0] == $nom) { 
	// /*1*/			$punts += $row[1];
	// /*1*/			$puntaverage += $row[2];
					// $participacions++;
	// /*1*/		}
	// /*1*/		else{
	// /*1*/			$taula[$i][0] = $nom;
					// if ($a == 'HistExtra') {
						// $redondeo = $punts / $participacions * $NUM_TEMPORADES;
						// $taula[$i][1] = round($redondeo,0);
						// $redondeo = $puntaverage / $participacions * $NUM_TEMPORADES; 
						// $taula[$i][2] = round($redondeo,0);
					// }
					// else {
	// /*1*/				$taula[$i][1] = $punts;
	// /*1*/				$taula[$i][2] = $puntaverage;
					// }
	// /*1*/			$nom = $row[0];
	// /*1*/			$punts = $row[1];	            
	// /*1*/			$puntaverage = $row[2];	    
					// $participacions = 1;
	// /*1*/					
	// /*1*/			$i++;
				// }
	// /*1*/	} 
			// while($row = mysql_fetch_array($result));
	// /*1*/	$taula[$i][0] = $nom;
			// if ($a == 'HistExtra') {
				// $redondeo = $punts / $participacions * $NUM_TEMPORADES;
				// $taula[$i][1] = round($redondeo,0);
				// $redondeo = $puntaverage / $participacions * $NUM_TEMPORADES; 
				// $taula[$i][2] = round($redondeo,0);
			// }
			// else {
	// /*1*/		$taula[$i][1] = $punts;
	// /*1*/		$taula[$i][2] = $puntaverage;
			// }
	// /*1*/}
		 // else {echo "PETADA RUBEN!!! Crec que torna la consulta buida, lo qual no pot ser";}
	
	// //2* Millor l'opció de dalt, no? Total, igual anava a fer while per a asignar la tabla... no aumente el cost, en canvi la consulta SQL sí seria més pesada
	// //2* SELECT c.id, c.nom, c.nom_equip, SUM(p.punts) as punts???  getPuntsTotals( ....
	// //2* etc...	
	// csort($taula, 1, SORT_DESC);	
	// }	
	// else{ // ANY CONCRET: tindrem en compte l'any en la SELECT
		// $sql = "SELECT concursant,punts,puntaverage from classificacions where tipo_class='$t' and any = $a ORDER BY puesto"; 
	
		// $result = mysql_query($sql, $link);

		// $taula = null;
		// if (mysql_num_rows($result)) {
			// $i = 0;
			// while($row = mysql_fetch_array($result)) {
				// $taula[$i] = $row;
				// $i++;
			// }
		// } else {echo "PETADA RUBEN!!! Crec que torna la consulta buida, lo qual no pot ser";}
	// }
		
	// return $taula;
// }
// */


function getClassHistoriques($a, $t)
{
global $NUM_TEMPORADES,$TEMP_ACTUAL;

	$link = conectar();
		
	if ($a == 'Històrica') {//HISTÒRICA  ignorarem l'any en la consulta SELECT		
		
	/*1*/$sql = "SELECT concursant,punts,puntaverage from classificacions where tipo_class='$t' and any <= $TEMP_ACTUAL ORDER BY concursant"; 
	/*1*/
	/*1*/$result = mysql_query($sql, $link);
	/*1*/
	/*1*/$taula = null;
	/*1*/if (mysql_num_rows($result)) {
	/*1*/	$i = 0;
	        $row = mysql_fetch_array($result);
	/*1*/	$nom = $row[0];
	/*1*/	$punts = 0;
	/*1*/	$puntaverage = 0;
			$participacions = 0;
			$redondeo = 0;
	/*1*/	do{
	/*1*/		if ($row[0] == $nom) { 
	/*1*/			$punts += $row[1];
	/*1*/			$puntaverage += $row[2];
					$participacions++;
	/*1*/		}
	/*1*/		else{
	/*1*/			$taula[$i][0] = $nom;
					$taula[$i][1] = $punts;
	/*1*/			$taula[$i][2] = $puntaverage;
	/*1*/			$punts = $row[1];	
					
	/*1*/			$nom = $row[0];            
	/*1*/			$puntaverage = $row[2];	    
					$participacions = 1;
	/*1*/					
	/*1*/			$i++;
				}
	/*1*/	} 
			while($row = mysql_fetch_array($result));
	/*1*/	$taula[$i][0] = $nom;
	/*1*/	$taula[$i][1] = $punts;
	/*1*/	$taula[$i][2] = $puntaverage;
	/*1*/}
		 else {echo "PETADA RUBEN!!! Crec que torna la consulta buida, lo qual no pot ser";}
	
	csort($taula, 1, SORT_DESC);	
	}	
	
	else if ($a == 'HistExtra') {// HISTÒRICA RATIO: ignorarem l'any en la consulta SELECT		
		
	/*1*/$sql = "SELECT concursant,punts,puntaverage,any from classificacions where tipo_class='$t' and any <= $TEMP_ACTUAL ORDER BY concursant"; 
	/*1*/
	/*1*/$result = mysql_query($sql, $link);
	/*1*/
	/*1*/$taula = null;
	/*1*/if (mysql_num_rows($result)) {
	/*1*/	$i = 0;
	        $row = mysql_fetch_array($result);
	/*1*/	$nom = $row[0];
	/*1*/	$punts = 0;
	/*1*/	$puntaverage = 0;
			$participacions = 0;
			$redondeo = 0;
	/*1*/	do{
				$any = $row[3];
				$sql2 = "SELECT max(punts) from classificacions where tipo_class='$t' and any = $any";
				$result2 = mysql_query($sql2, $link);
				$maxpunts = NULL;	
				$maxpunts = mysql_fetch_array($result2);
	/*1*/		if ($row[0] == $nom) { 
	/*1*/			$punts += $row[1] / $maxpunts[0];
	/*1*/			$puntaverage += $row[2];
					$participacions++;
	/*1*/		}
	/*1*/		else{
	/*1*/			$taula[$i][0] = $nom;
					$redondeo = $punts / $participacions * 100;
					$taula[$i][1] = round($redondeo,2);
					$redondeo = $puntaverage / $participacions * $NUM_TEMPORADES; 
					$taula[$i][2] = round($redondeo,0);
	/*1*/			$punts = $row[1] / $maxpunts[0];	
					
	/*1*/			$nom = $row[0];            
	/*1*/			$puntaverage = $row[2];	    
					$participacions = 1;
	/*1*/					
	/*1*/			$i++;
				}
	/*1*/	} 
			while($row = mysql_fetch_array($result));
	/*1*/	$taula[$i][0] = $nom;
			$redondeo = $punts / $participacions * 100;
			$taula[$i][1] = round($redondeo,2);
			$redondeo = $puntaverage / $participacions * $NUM_TEMPORADES; 
			$taula[$i][2] = round($redondeo,0);
		
	/*1*/}
		 else {echo "PETADA RUBEN!!! Crec que torna la consulta buida, lo qual no pot ser";}

	csort($taula, 1, SORT_DESC);	
	}	
		
	else{ // ANY CONCRET: tindrem en compte l'any en la SELECT
		$sql = "SELECT concursant,punts,puntaverage from classificacions where tipo_class='$t' and any = $a ORDER BY puesto"; 
	
		$result = mysql_query($sql, $link);

		$taula = null;
		if (mysql_num_rows($result)) {
			$i = 0;
			while($row = mysql_fetch_array($result)) {
				$taula[$i] = $row;
				$i++;
			}
		} else {echo "PETADA RUBEN!!! Crec que torna la consulta buida, lo qual no pot ser";}
	}
		
	return $taula;
}




/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
./·':-                                           ./·':-
./·':-           getPalmarésPerJugador           ./·':-
./·':-                                           ./·':-
./·':-   Consulta el palmarés d'un participant   ./·':-
./·':-                                           ./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·'*/

//retorna un vector amb tantes files com títols tinga: Tipo_class (Any).  Exemple: Acumulada (2009)
// function getPalmarésPerJugador($c)
// {
// //obtindre "concursant" del get de pagina

	// $link = conectar();

	// $sql = "SELECT concursant,tipo_class,any,puesto * FROM classificacions WHERE concursant = $c and puesto = 1";
	
	// $result = mysql_query($sql, $link);

	// $taula = null; //una fila per cada títol
	// if (mysql_num_rows($result)) {
		// $i = 0;
		// while($row = mysql_fetch_array($result)) {
			// $taula[$i] = $row;
			// $i++;
		// }
	// } else {echo "Aquest jugador no té cap títol";}

	// return $taula;
// }


/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
./·':-                                           ./·':-
./·':-           guardar_classificacio           ./·':-
./·':-                                           ./·':-
./·':-   Guarda les classificacions de l'últim   ./·':-
./·':-   any en la tabla de class_historica      ./·':-
./·':-                                           ./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·':-
/*./·':-./·':-./·':-./·':-./·':-./·':-./·':-./·'*./·'*/

// $a -> any
// $t -> tipo_class
function guardar_classificacio($a, $t) // intentem passar-li ANY ACTUAL pa que no s'actualitze una de les ja posades...
{
	$link = conectar();
	
	$jornada = 38;
	
	$class_acumulada = getPuntsTotals($jornada); // Obtenim una tabla els punts totals fins la joranda 38 per a cada jugador
		
	$sql = "SELECT punts from classificacions where any = $a";
	$result = mysql_query($sql, $link);
	
	if ($t=='Acumulada') {
		if ($row = mysql_fetch_array($result)){	
			for($i = 0; $i < sizeof($class_acumulada); $i++) {
				$apunts = $class_acumulada[$i][3];
				$aconcur = $class_acumulada[$i][1];
				$apuesto = $i + 1;
				$sql = "UPDATE classificacions set punts = $apunts, puesto = $i+1 WHERE concursant = '$aconcur' AND any = $a AND tipo_class = '$t' ";	
				$result = mysql_query($sql, $link);

			}
		}
		else{
			for($i = 0; $i < sizeof($class_acumulada); $i++) {
				$apunts = $class_acumulada[$i][3];
				$aconcur = $class_acumulada[$i][1];
				$apuesto = $i + 1;
			//	$sql = "INSERT INTO classificacions ( tipo_class , any , concursant , punts , puesto ) VALUES ( 'Acumulada' , $a, $class_acumulada[$i][1] , $class_acumulada[$i][3] , i+1 ) "; 
				$sql = "INSERT INTO classificacions ( tipo_class , any , concursant , punts , puesto) VALUES ( 'Acumulada' , $a, '$aconcur' , $apunts, $apuesto) "; 
					
				$result = mysql_query($sql, $link);
			}
		}
	}
	else if ($t=='General') {
		if ($row = mysql_fetch_array($result)){	
			for($i = 0; $i < sizeof($class_acumulada); $i++) {
				$apunts = $class_acumulada[$i][3];
				$aconcur = $class_acumulada[$i][1];
				$apuesto = $i + 1;
				$sql = "UPDATE classificacions set punts = $apunts, puesto = $i+1 WHERE concursant = '$aconcur' AND any = $a AND tipo_class = '$t' ";	
				$result = mysql_query($sql, $link);

			}
		}
		else{
			for($i = 0; $i < sizeof($class_acumulada); $i++) {
				$apunts = $class_acumulada[$i][3];
				$aconcur = $class_acumulada[$i][1];
				$apuesto = $i + 1;
			//	$sql = "INSERT INTO classificacions ( tipo_class , any , concursant , punts , puesto ) VALUES ( 'Acumulada' , $a, $class_acumulada[$i][1] , $class_acumulada[$i][3] , i+1 ) "; 
				$sql = "INSERT INTO classificacions ( tipo_class , any , concursant , punts , puesto) VALUES ( 'Acumulada' , $a, '$aconcur' , $apunts, $apuesto) "; 
					
				$result = mysql_query($sql, $link);
			}
		}
	}
	return "La Classificació $t ha segut actualitzada.";
}

function getEsNomUnic($nom,$ideq)  //obligat a passar equipo (desplegable en html, que per defecte portarà "Equip")
{
	$link = conectar();
	$nom_utf8=utf8_decode($nom);		
	if ($ideq == 99){
		$sql = "SELECT j.id,j.nom,e.nom,j.valor,e.sigles,e.id,j.posicio FROM jugadors j, equips e WHERE j.equip = e.id AND  j.nom like '%$nom_utf8%'"; 
	}
	else if ($ideq == 100){// busquem per id jugador (chapuza, pero l'hem guardat en $nom)
		$sql = "SELECT j.id,j.nom,e.nom,j.valor,e.sigles,e.id,j.posicio FROM jugadors j, equips e WHERE j.equip = e.id AND  j.id = '$nom_utf8'"; 
	}
	else{ //consultem per nom també
		$sql = "SELECT j.id,j.nom,e.nom,j.valor,e.sigles,e.id,j.posicio FROM jugadors j, equips e WHERE j.equip = e.id AND  j.nom like '%$nom_utf8%' AND  j.equip = $ideq"; 
	}
	
	$result = mysql_query($sql, $link);
	
	$taula = NULL;

	//if (mysql_num_rows($result)) {
	if ($result != NULL) {
		$i=0;
		while($row = mysql_fetch_array($result)) {
			$taula[$i] = $row;
			$i++;
		}
	}
	return $taula;
}


function getQuiTeAquestJugador($idjug,$eq) {

	$link = conectar();
	
	$jornadaActual = getJornadaActual();
		
	$sql = "SELECT c.nom,je.seleccionat FROM concursants c, jugadors_elegits je"; //count per a número de concursants que el tenen (indiquem el idjugador en group by)
	$sql .= " WHERE c.id = je.idConcursant";
	$sql .= " AND je.idjugador = $idjug AND je.jornada = $jornadaActual ";  //Lo de jornada = 0 deu ser algo de la tabla de Jose
	$sql .= " ORDER BY c.nom";
	//$sql .= " GROUP BY je.idJugador";
		
	$result = mysql_query($sql, $link);

	$taula = NULL;
	
	if (mysql_num_rows($result)) {//Guardam els propietaris (i el nº de propietaris serà el número de files)
		$i=0;
		while($row = mysql_fetch_array($result)) {
			$taula[$i] = $row;
			$i++;
		}
	}
	
//Podriem haver fet tot lo que ve i els participants totals en una nova funció per a sols retornar un vector...
//Calcula punts jugador:

	$sql = "SELECT sum(punts) FROM punts_jornada";  
	$sql .= " WHERE idJugador = $idjug"; //agarrarà totes les jornades  (també podría haver posat AND jornada <= 36... pero pa que... si son totes... 
														// o AND jornada < getJornadaActual()) (tampoc fa falta)
	
	$result = mysql_query($sql, $link);
	
	$punts = NULL;

	if (mysql_num_rows($result) > 0){
		$punts = mysql_fetch_array($result);
	}

// Per al número de partits:

	$sql = "SELECT count(*) FROM punts_jornada";  
	$sql .= " WHERE idJugador = $idjug AND punts >= -10";  // Si no tenen per defecte els punts a 0...   AND jornada < getJornadaActual()) (tampoc fa falta)
	
	$result = mysql_query($sql, $link);

	$partits = NULL;

	if (mysql_num_rows($result) > 0){
		$partits= mysql_fetch_array($result);
		if ($partits[0] > 0) 	$mitja = $punts[0] / $partits[0];
	}

	$taula [0][2] = $punts[0];
	$taula [0][3] = $partits[0];
	$taula [0][4] = round($mitja,2); //falta el rounded
	$taula [0][5] = $i ;//Nºde files (propietaris)

	return $taula;
}


//update jugador
function updateEquipJugador($id, $eq) {
	$link = conectar();

	$sql = "UPDATE jugadors SET equip = $eq WHERE id = $id";
	$result = mysql_query($sql, $link);
}


function enviarEquipsSencers()
{
	$llistaCon = getTaula("concursants", "usuari");

	$salida = "EQUIPS SENCERS \n\n";

	for ($i = 0; $i < sizeof($llistaCon); $i++) {

		$salida .= "\n\n" . $llistaCon[$i]["usuari"] . ": ";

		$jugadors = getJugadorsPerPersona($llistaCon[$i]["id"], 0);
		for ($j = 0; $j < sizeof($jugadors); $j++) {
				$salida .= $jugadors[$j]["nom"] . "(" . $jugadors[$j]["sigles"] . "); ";
		}
	}
    return $salida;
}


function InsertaLog ($concur,$usuari_mod,$alineacio,$data,$hora) {

	$link = conectar();
	
	$ok = false;
	
//	$sql = "INSERT INTO alineacions  VALUES ('','$concur','$usuari_mod','$alineacio',$data,$hora) "; 
	$sql = "INSERT INTO alineacions ( Concur, Usu_mod, jugadors, data, hora) VALUES ('$concur','$usuari_mod','$alineacio','$data','$hora') "; 

	$result = mysql_query($sql, $link);

	if ($result)
		$ok = true;
	
	return $ok;
}


function EscriuLOG($accio, $idjug, $v, $nom_c, $usu_mod) {

	$link = conectar();

	$ip = $_SERVER['REMOTE_ADDR']; 
	
	$nom_arxiu = quitarAcentos("$nom_c");
	$nom_arxiu .= ".txt";
	
	putenv('TZ=Europe/Madrid');  
	$dia = strftime("%d/%m/%Y", time());
	$hora = date("H:i:s",time());
	
	
	if ($accio == 'seleccio'){
	
		if ($v == 1) 
			$selec = 'titular';
		else 
			$selec = 'suplent';
			
		$sql = "SELECT j.nom,e.sigles FROM jugadors j, equips e WHERE j.equip = e.id AND  j.id = $idjug"; 
		$result = mysql_query($sql, $link);
		$row = mysql_fetch_array($result);
			
		//$linea = "[" . $row[0] . "(" . $row[1] . ")] \t [" . $selec . "] \t [" . $nom_c . "] \t [" . $usu_mod . "] \t [" . $ip . "] \t [" . $dia . "] \t [" . $hora . "] \n";
		$col1 = "[" . $row[0] . "(" . $row[1] . ")]                                         ";
		$col2 = "[" . $selec . "]                                                           ";
		$col3 = "[" . $nom_c . "]                                                           ";
		$col4 = "[" . $usu_mod . "]                                                         ";
		$col5 = "[" . $ip . "]                                                              ";
		$col6 = "[" . $dia . "]                                                             ";
		$col7 = "[" . $hora . "]\n";
		
	}
	
	else if ($accio == 'AfegeixJug' or $accio == 'AnulaJug'){
			
		$sql = "SELECT j.nom,e.sigles FROM jugadors j, equips e WHERE j.equip = e.id AND  j.id = $idjug"; 
		$result = mysql_query($sql, $link);
		$row = mysql_fetch_array($result);
			
		//$linea = "[" . $row[0] . "(" . $row[1] . ")] \t [" . $accio . "] \t [" . $nom_c . "] \t [" . $usu_mod . "] \t [" . $ip . "] \t [" . $dia . "] \t [" . $hora . "] \n";
		$col1 = "[" . $row[0] . "(" . $row[1] . ")]                                         ";
		$col2 = "[" . $accio . "]                                                           ";
		$col3 = "[" . $nom_c . "]                                                           ";
		$col4 = "[" . $usu_mod . "]                                                         ";
		$col5 = "[" . $ip . "]                                                              ";
		$col6 = "[" . $dia . "]                                                             ";
		$col7 = "[" . $hora . "]\n";

	}
	
	else if ($accio == 'Estratègia' or $accio == 'TotsSuplen'){						
		//$linea = " ------------- \t" . "[Estratègia] \t [" . $nom_c . "] \t [" . $usu_mod . "] \t [" . $ip . "] \t [" . $dia . "] \t [" . $hora . "] \n";
		$col1 = "[---------------------]                                                    ";
		$col2 = "[" . $accio . "]                                                           ";
		$col3 = "[" . $nom_c . "]                                                           ";
		$col4 = "[" . $usu_mod . "]                                                         ";
		$col5 = "[" . $ip . "]                                                              ";
		$col6 = "[" . $dia . "]                                                             ";
		$col7 = "[" . $hora . "]\n";

	}


	else{
		die("Ai mare! Açò no és possible!");
	}
	$dir = 'LOGS';
	$fp = fopen($dir."/".$nom_arxiu, "a")or die("Impossible obrir arxiu '$nom_arxiu'\n");
	//fputs($fp, $linea);
	fputs($fp, $col1, 25);
	fputs($fp, $col2, 15);
	fputs($fp, $col3, 20);
	fputs($fp, $col4, 20);
	fputs($fp, $col5, 20);
	fputs($fp, $col6, 15);
	fputs($fp, $col7, 15);
	fclose($fp);
	

}
//[Rosenberg(RAC)] 	 [titular] 	 [Rubén Aparici] 	 [Rubén Aparici] 	 [89.128.68.24] 	 [12/11/2010] 	 [21:33:19] 
//[Saul(DEP)] 		 [suplent] 	 [Rubén Aparici] 	 [Rubén Aparici] 	 [89.128.68.24] 	 [12/11/2010] 	 [21:32:41] 
//                                                                                                                       [Rosenberg(RAC)][suplent][Rubén Aparici][Rubén Aparici][89.128.68.24][12/11/2010][22:17:44]
 
 
 
 
 
 

//PENDENT: Descarregar equips sencers (paregut a descarregar alineacions)
/*


- - Obtenim el màxim de punts obtinguts en una temporada (per al palmarés)
SELECT tipo_class, any, max(punts)  FROM classificacions WHERE concursant = $c  AND tipo_class = 'Acumulada' OR tipo_class = 'General' GROUP BY (tipo_class) ORDER BY tipo_class
//supose que obtindrem el màxim del concursant consultat en l'Acumulada i en la General

*/
?>