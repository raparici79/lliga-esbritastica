<?php
/************************************************
*	File: 	funcions.php						*
*	Desc: 	biblioteca de funcions.				*
*	Author:	Jose Gargallo 						*
*****************f*******************************/

//Funcio per a conectar amb la bbdd
function conectar()
{
	global $BD_host,$BD_user,$BD_passwd,$BD_nombre;

   	if (!($link=mysql_connect("$BD_host","$BD_user","$BD_passwd")))
   	{
      		echo "ERROR: Conectant en la base de dades";
      		exit();
   	}
   	if (!mysql_select_db("$BD_nombre",$link))
   	{
      		echo "ERROR: Seleccionant la base de dades";
      		exit();
   	}
   	return $link;
}

//Torna en un vector la info de tots els equips de la lliga
function getTaula($nom_taula, $ordre)
{
	$link = conectar();
	$sql = "SELECT * from " . $nom_taula;
	if ($ordre != "")
		$sql .= " ORDER BY " . $ordre;
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

//Torna un vector amb els jugadors
function getJugadors()
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, e.sigles, j.posicio, j.valor, j.ecomunitari";
	$sql .= " FROM jugadors j, equips e";
	$sql .= " WHERE j.equip = e.id";
	$sql .= " ORDER BY j.posicio asc, e.sigles asc, j.nom asc";

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

//Torna la info de un concursant
function getInfoConcursant($id) {
	$link = conectar();
	$sql = "SELECT * from concursants WHERE id = $id";
	
	$result = mysql_query($sql, $link);
	$row = mysql_fetch_array($result);
	return $row;
}

//Torna la info de un jugador
function getInfoJugador($id) {
	$link = conectar();
	$sql = "SELECT * from jugadors WHERE id = $id";
	
	$result = mysql_query($sql, $link);
	$row = mysql_fetch_array($result);
	return $row;
}

//RAB: Per al format imprimible que he fet.
function getJugadorsPerEquipImpr($equip)
{
	$link = conectar();
	
	//$sql = "SELECT id, nom, equip, posicio, valor, ecomunitari from jugadors";
	//$sql .= " WHERE equip = " . $equip;

    $sql = "SELECT j.id, j.nom, j.posicio, e.sigles, j.valor, j.ecomunitari,e.nom as nomeq from jugadors j, equips e";
    $sql .= " WHERE j.equip = e.id AND equip = " . $equip;
		
	$sql .= " ORDER BY posicio, j.nom";
	
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

//Torna en un vector tots els jugadors d'un equip concret
function getJugadorsPerEquip($equip)
{
	$link = conectar();
	$sql = "SELECT id, nom, posicio, valor, ecomunitari from jugadors";
	$sql .= " WHERE equip = " . $equip;
	$sql .= " ORDER BY posicio,nom";
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

//Torna un vector amb la llista de jugadors per a cada posicio
function getJugadorsPerPosicio($pos)
{
	$link = conectar();
        $sql = "SELECT j.id, j.nom, e.sigles, j.valor, j.ecomunitari from jugadors j, equips e";
        $sql .= " WHERE j.equip = e.id AND posicio = " . $pos;
	$sql .= " ORDER BY sigles, equip, nom asc";
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

//Torna els jugadors de un determinat concursant i jornada
function getJugadorsPerPersona($persona, $jornada)
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, e.sigles, j.posicio, j.valor, j.ecomunitari, je.seleccionat";
	$sql .= " FROM jugadors_elegits je, jugadors j, equips e";
	$sql .= " WHERE je.idJugador = j.id AND j.equip = e.id";
	$sql .= " AND je.jornada = " . $jornada . " AND je.idConcursant = " . $persona;
	$sql .= " ORDER BY posicio asc, seleccionat desc, nom asc";

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

//Mostrem els jugadors que tenen 5 concursants o més per a bloquejar-los després
function getJugadorsBloquejats()
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, e.sigles, j.posicio, j.valor, j.ecomunitari";
	$sql .= " FROM jugadors_elegits je, jugadors j, equips e";
	$sql .= " WHERE je.idJugador = j.id AND j.equip = e.id";
	$sql .= " AND je.jornada = 0";
	$sql .= " GROUP BY je.idJugador";
	$sql .= " HAVING COUNT(je.idConcursant) >= 5";
	$sql .= " ORDER BY j.ecomunitari, e.sigles asc";

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


function getJugadorsRival($persona, $jornada)
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, e.sigles, j.posicio, j.valor, j.ecomunitari, je.seleccionat";
	$sql .= " FROM jugadors_elegits je, jugadors j, equips e";
	$sql .= " WHERE je.idJugador = j.id AND j.equip = e.id";
	$sql .= " AND je.jornada = " . $jornada . " AND je.idConcursant = " . $persona;
	$sql .= " ORDER BY posicio asc, nom asc";

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

//torna el mateix vector q l'anterior pero afegix els punts de la jornada
function getPuntsJugadorsPerPersona($persona, $jornada)
{
        $link = conectar();
        $sql = "SELECT j.id, j.nom, e.sigles, j.posicio, j.valor, j.ecomunitari, je.seleccionat, p.punts";
        $sql .= " FROM jugadors_elegits je, jugadors j, punts_jornada p, equips e";
        $sql .= " WHERE je.idJugador = j.id AND j.id = p.idJugador AND j.equip = e.id";
	$sql .= " AND je.jornada = " . $jornada . " AND je.jornada = p.jornada AND je.idConcursant = " . $persona;
        $sql .= " ORDER BY seleccionat desc, posicio asc, sigles asc, nom asc";

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

//torna la maxima puntuacio possible per a una posicio
function getPuntsMaximsPerPosicio($persona, $jornada, $pos, $numJugadors)
{
        $link = conectar();

	$sql = "select sum(pj.punts) from jugadors_elegits je, punts_jornada pj, jugadors j where je.idJugador = pj.idJugador and pj.idJugador = j.id and je.jornada = pj.jornada and je.idConcursant = $persona and je.jornada = $jornada and j.posicio = $pos order by punts desc limit $numJugadors";

        $result = mysql_query($sql, $link);
        $row = mysql_fetch_array($result);

	return $row[0];
}


//Torna un vector amb els punts de cada concursant per a una jornada
function getPuntsPerJornada($jornada)
{
	$link = conectar();
	$sql = "SELECT c.id, c.nom, c.nom_equip, SUM(p.punts) as punts";
	$sql .= " FROM concursants c, jugadors_elegits je, punts_jornada p";
	$sql .= " WHERE c.id = je.idConcursant AND je.idJugador = p.idJugador";
	$sql .= " AND je.jornada = $jornada AND je.jornada = p.jornada AND je.seleccionat = 1";
	$sql .= " GROUP BY c.id ORDER BY punts desc";

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

//Torna un vector amb els punts de un concursant per a una jornada
function getPuntsPerJornadaYConcursant($jornada,$con)
{
	$link = conectar();
	$sql = "SELECT c.id, c.nom, c.nom_equip, SUM(p.punts) as punts";
	$sql .= " FROM concursants c, jugadors_elegits je, punts_jornada p";
	$sql .= " WHERE c.id = je.idConcursant AND je.idJugador = p.idJugador";
	$sql .= " AND je.jornada = $jornada AND je.jornada = p.jornada AND je.seleccionat = 1 AND c.id = $con";
	$sql .= " GROUP BY c.id";

	$result = mysql_query($sql, $link);
	$row = mysql_fetch_array($result);
	return $row;
}

//Torna un vector amb les ganancies de cada concursant fins la jornada indicada
function getGanancies($jornada)
{
	$concursants = getTaula("concursants", "nom asc");
	for ($i = 1 ; $i <= $jornada ; $i++)
	{
		$clas = getPuntsPerJornada($i);
		$primer = $clas[0];
		$segon = $clas[1];
		$tercer = $clas[2];
		for ($j = 1 ; $j < sizeof($concursants) ; $j++)
		{
			//FALTA TERMINARLO, DEMASIAO COMPLICAO PA UN SABADO	
		}

	}
}

//Torna un vector amb els punts totals fins la ultima jornada disputada
function getPuntsTotals($jornada)
{
        $link = conectar();
        $sql = "SELECT c.id, c.nom, c.nom_equip, SUM(p.punts) as punts";
        $sql .= " FROM concursants c, jugadors_elegits je, punts_jornada p";
        $sql .= " WHERE c.id = je.idConcursant AND je.idJugador = p.idJugador";
        $sql .= " AND je.jornada = p.jornada AND je.seleccionat = 1 AND p.jornada <= $jornada";
        $sql .= " GROUP BY c.id ORDER BY punts desc";

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

//ordena una matriu bidimensional per una de les columnes
//descendent: SORT_DESC
//ascendent: SORT_ASC
function csort($array, $column, $orden){   
  $i=0;
  for($i=0; $i<count($array); $i++){
    $sortarr[]=$array[$i][$column];
  }
 
  array_multisort($sortarr, $orden, $array); 
   
  return($array);
}

//el mateix pero pots ordenar per mes d'una columna
function array_csort() {
    $args = func_get_args();
    $marray = array_shift($args);

    $msortline = "return(array_multisort(";
    foreach ($args as $arg) {
        $i++;
        if (is_string($arg)) {
            foreach ($marray as $row) {
                $sortarr[$i][] = $row[$arg];
            }
        } else {
            $sortarr[$i] = $arg;
        }
        $msortline .= "\$sortarr[".$i."],";
    }
    $msortline .= "\$marray));";

    eval($msortline);
    return $marray;
}



//Torna un vector amb els punts de la classificació d'enfrontaments
function getPuntsGeneral($jornada)
{
        $link = conectar();
		
		$aCon = getTaula("concursants", "usuari");
		
		for ($i = 0; $i < sizeof($aCon); $i++) {
			$puntsTotals = 0;
			$puntaverage = 0;
			$idCon = $aCon[$i]["id"];
			for ($j = 1; $j <= $jornada; $j++) {
				$punts = getPuntsPerJornadaYConcursant($j, $aCon[$i]["id"]);
				$sql = "SELECT idJugador1, idJugador2 FROM enfrontaments WHERE jornada = $j";
				$sql .= " AND (idJugador1 = $idCon OR idJugador2 = $idCon)";
				$result = mysql_query($sql, $link);
				if (mysql_num_rows($result) > 0) {
					$row = mysql_fetch_array($result);
					if ($row["idJugador1"] == $idCon)
						$punts2 = getPuntsPerJornadaYConcursant($j, $row["idJugador2"]);
					else
						$punts2 = getPuntsPerJornadaYConcursant($j, $row["idJugador1"]);
					if ($punts["punts"] > $punts2["punts"])
						$p = 3;
					else if ($punts["punts"] == $punts2["punts"] && $punts2["punts"] != null)
						$p = 1;
					else
						$p = 0;
					$dif = $punts["punts"] - $punts2["punts"];
				}
				else {
					$p = 0;
					$dif = 0;
				}
					
				$puntsTotals += $p;
				$puntaverage += $dif;
			}
			//Penalityzacions
			$puntsTotals += getPenalitzacio($idCon);

			$aCon[$i]["punts"] = $puntsTotals;

			$aCon[$i]["puntaverage"] = $puntaverage;
		}
		
		//return csort($aCon, "punts", SORT_DESC);
		return array_csort($aCon, "punts", SORT_DESC, "puntaverage", SORT_DESC);
}

//Torna un vector amb els punts de cada jugador en una jornada
function getPuntsJugadors($jornada)
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, e.sigles, j.posicio, j.valor, j.ecomunitari";
	if ($jornada > 0) $sql .= ", p.punts";
	$sql .= " FROM jugadors j, equips e";
	if ($jornada > 0) $sql .= ", punts_jornada p";
	$sql .= " WHERE j.equip = e.id";
	if ($jornada > 0) $sql .= " AND j.id = p.idJugador AND p.jornada = " . $jornada;
	$sql .= " ORDER BY j.posicio asc, e.sigles asc, j.nom asc";

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

//Torna un vector amb els punts de cada jugador d'un equip en una jornada
function getPuntsJugadorsPerEquip($jornada, $equip)
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, j.posicio, j.valor, j.ecomunitari, p.punts";
	//if ($jornada > 0) $sql .= ", p.punts";
	$sql .= " FROM jugadors j LEFT JOIN punts_jornada p ON j.id = p.idJugador AND p.jornada = $jornada";
	//if ($jornada > 0) $sql .= ", punts_jornada p";
	$sql .= " WHERE j.equip = $equip AND (p.jornada = $jornada OR p.idJugador IS NULL)";
	//if ($jornada > 0) $sql .= " AND j.id = p.idJugador AND p.jornada = " . $jornada;
	$sql .= " ORDER BY j.posicio asc, j.nom asc";
//	echo $sql;
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


//Torna un vector amb els punts totals de cada jugador fins al moment
function getPuntsJugadorsTotal($jornada)
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, e.sigles, j.posicio, j.valor, j.ecomunitari";
	if ($jornada > 0) $sql .= ", SUM(p.punts) as punts";
	$sql .= " FROM jugadors j, equips e";
	if ($jornada > 0) $sql .= ", punts_jornada p";
	$sql .= " WHERE j.equip = e.id";
	if ($jornada > 0) $sql .= " AND j.id = p.idJugador AND p.jornada <= " . $jornada;
	$sql .= " GROUP BY j.id";
	$sql .= " ORDER BY j.posicio asc, e.sigles asc, j.nom asc";

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

//Torna un vector amb els punts totals de cada jugador fins al moment
function getPuntsJugadorsTotalPerEquip($jornada, $equip)
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, j.posicio, j.valor, j.ecomunitari";
	if ($jornada > 0) $sql .= ", SUM(p.punts) as punts";
	$sql .= " FROM jugadors j";
	if ($jornada > 0) $sql .= ", punts_jornada p";
	$sql .= " WHERE j.equip = $equip";
	if ($jornada > 0) $sql .= " AND j.id = p.idJugador AND p.jornada <= " . $jornada;
	$sql .= " GROUP BY j.id";
	$sql .= " ORDER BY j.posicio asc, j.nom asc";

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


//Torna un vector amb els punts de cada jugador en una jornada
function getPuntsJugadorsPerConcursant($con, $jornada)
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, e.sigles, j.posicio, j.valor, j.ecomunitari, p.punts";
	$sql .= " FROM jugadors j, equips e, punts_jornada p, jugadors_elegits je";
	$sql .= " WHERE j.equip = e.id AND j.id = p.idJugador AND je.idJugador = p.idJugador AND je.idConcursant = $con AND p.jornada = je.jornada AND p.jornada = $jornada AND je.seleccionat = 1";
	$sql .= " ORDER BY j.posicio asc, e.sigles asc, j.nom asc";
//	echo $sql;
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

//Torna un vector amb els punts totals de cada jugador d'un concursant fins al moment
function getPuntsJugadorsPerConcursantTotal($con, $jornada)
{
	$link = conectar();
	$sql = "SELECT j.id, j.nom, e.sigles, j.posicio, j.valor, j.ecomunitari, SUM(p.punts) as punts";
	$sql .= " FROM jugadors j, equips e, punts_jornada p, jugadors_elegits je";
	$sql .= " WHERE j.equip = e.id AND j.id = p.idJugador AND je.idJugador = p.idJugador AND p.jornada = je.jornada AND je.idConcursant = $con AND p.jornada <= $jornada AND je.seleccionat = 1";
	$sql .= " GROUP BY j.id";
	$sql .= " ORDER BY j.posicio asc, e.sigles asc, j.nom asc";

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


//Torna el valor duna variable de configuracio
function getValorConfiguracio($var) {
	$link = conectar();
	$sql = "SELECT valor FROM configuracio WHERE nom = '$var'";

	$result  = mysql_query($sql, $link);
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		return $row["valor"];
	}
	return null;
}

function getJornadaActual() {
	return getValorConfiguracio("jornada_actual");
}

//torna un vector en la data limit per a inscriure l'equip titular
function getDataLimit () {
	$str = getValorConfiguracio("data_limit");
	$vec = explode("-", $str);
	$dia = explode("/", $vec[0]);
	$hora = explode(":", $vec[1]);
	
	$data["dia"] = $dia[0];
	$data["mes"] = $dia[1];
	$data["any"] = $dia[2];
	$data["hora"] = $hora[0];
	$data["minut"] = $hora[1];
	
	return $data;
}
/*RAB

//comprova si el equip es vol retocar fora de la data limit
function foraLimit() {
	global $ADMINISTRADOR;
	$limit = getDataLimit();
	$ara = time();
        //LE RESTAMOS 2 HORAS PQ ES LA DIFERENCIA HORARIA DEL SERVIDOR AWARDSPACE.COM
	$hLimit = mktime($limit["hora"] - 1, $limit["minut"], 0, $limit["mes"], $limit["dia"], $limit["any"]);
	return ($ara > $hLimit && $_SESSION["userid"] != $ADMINISTRADOR );
}

//comprova si ha passat el limit i es poden mostrar els equips
function foraLimitRivals() {
	$limit = getDataLimit();
	$ara = time();
	$hLimit = mktime($limit["hora"] - 1, $limit["minut"], 0, $limit["mes"], $limit["dia"], $limit["any"]);

	return $ara > $hLimit;
}

RAB */

/*RAB: He modificat la funció per a obtindre l'hora espanyola i no tindre que canviar-la cada volta*/

//comprova si el equip es vol retocar fora de la data limit
function foraLimit() {
	global $ADMINISTRADOR;
	$limit = getDataLimit();
	putenv('TZ=Europe/Madrid');  //Per a obtindre l'hora espanyola
	$ara = time();
	$hLimit = mktime($limit["hora"], $limit["minut"], 0, $limit["mes"], $limit["dia"], $limit["any"]);
	return ($ara > $hLimit && $_SESSION["userid"] != $ADMINISTRADOR );
}

//comprova si ha passat el limit i es poden mostrar els equips
function foraLimitRivals() {
	$limit = getDataLimit();
	putenv('TZ=Europe/Madrid');  //Per a obtindre l'hora espanyola
	$ara = time();
	$hLimit = mktime($limit["hora"], $limit["minut"], 0, $limit["mes"], $limit["dia"], $limit["any"]);

	return $ara > $hLimit;
}
/*RAB*/

//anyadix un jugador a la llista de selecionats d'un concursant
function addJugador($idCon, $idJug, $jor, $sel) {
	global $NUM_JUGADORS, $NUM_ECOMUNITARIS, $PRESUPOST, $NUM_MATEIX_EQUIP;
	$link = conectar();
	$sql = "SELECT * FROM jugadors_elegits WHERE idConcursant = $idCon AND idJugador = $idJug AND jornada = $jor";
	$result = mysql_query($sql, $link);
	$ok = true;
	if (mysql_num_rows($result) > 0) {
		$ok = false;
		return "Aquest jugador ja ha estat elegit!";
	}
	
	$sql = "SELECT * FROM jugadors_elegits WHERE idConcursant = $idCon AND jornada = $jor";
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) >= $NUM_JUGADORS) {
		$ok = false;
		return "Ja ha elegit tots els Jugadors, per a elegir un altre borre un ja existent.";
	}
	
	$infoJug = getInfoJugador($idJug);
	if ($infoJug["ecomunitari"] == 1) {
		$sql = "SELECT * FROM jugadors j, jugadors_elegits e WHERE j.id = e.idJugador AND e.idConcursant = $idCon AND e.jornada = $jor AND j.ecomunitari = 1";
		$result = mysql_query($sql, $link);
		if (mysql_num_rows($result) >= $NUM_ECOMUNITARIS) {
			$ok = false;
			return "Sols pots tindre $NUM_ECOMUNITARIS jugadors extracomunitaris";
		}
	}
	
	$sql = "SELECT count(*) as num FROM jugadors j, jugadors_elegits e WHERE j.id = e.idJugador AND e.idConcursant = $idCon AND e.jornada = $jor AND j.equip =" . $infoJug["equip"];
	$result = mysql_query($sql, $link);
	$row = mysql_fetch_array($result);
	if ($row["num"] >= $NUM_MATEIX_EQUIP) {
		$ok = false;
		return "Ja tens $NUM_MATEIX_EQUIP jugadors del mateix equip";
	}
	
	$sql = "SELECT SUM(j.valor) as total FROM jugadors j, jugadors_elegits e WHERE j.id = e.idJugador AND e.idConcursant = $idCon AND e.jornada = $jor";
	$result = mysql_query($sql, $link);
	$row = mysql_fetch_array($result);
	if ( $row["total"] + $infoJug["valor"] > $PRESUPOST) {
		$ok = false;
		return "T'has passat del presupost ($PRESUPOST)";
	}	
	
	$sql = "select nom from concursants where estrella = $idJug and id != $idCon";
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) > 0) {
		$ok = false;
		$row = mysql_fetch_array($result);
		return "És Jugador Estrella de " . $row["nom"];
	}

	if ($ok) {
		$sql = "INSERT INTO jugadors_elegits VALUES($idCon,$idJug,$jor,$sel)";
		$result = mysql_query($sql, $link);
	}
}
//canvia de estrategia
function canviarEstrategia($idCon, $jor, $estrat) {
	$link = conectar();
	$sql = "UPDATE concursants SET estrategia = $estrat WHERE id = $idCon";

	$result  = mysql_query($sql, $link);
	
	//al canviar de estrategia tots els jugadors de ixa jornada pasen a ser suplents
	//totsSuplents($idCon, $jor);
}

//tots els jugadors de la jornada pasen a ser suplents (canvi de estrategia)
function totsSuplents($idCon, $jor) {
	$link = conectar();
	$sql = "UPDATE jugadors_elegits SET seleccionat = 0 WHERE idConcursant = $idCon AND jornada = $jor";
	$result = mysql_query($sql, $link);
}

//desglosa la estrategia
function desglosaEstrategia ($est) {
	$estrategia[4] = substr($est, 2, 1);
	$estrategia[3] = substr($est, 1, 1);
	$estrategia[2] = substr($est, 0, 1);
	$estrategia[1] = 1;
	
	return $estrategia;
}

//activa la jornada q es passa per param
function activarJornada($jornada, $limit) {
	global $JORNADA_BASE;
	$link = conectar();
	
	$sql = "UPDATE configuracio SET valor = '$jornada' WHERE nom LIKE 'jornada_actual'";
	$result = mysql_query($sql, $link);

        $sql = "UPDATE configuracio SET valor = '0' WHERE nom LIKE 'aliniacions_enviades'";
	$result = mysql_query($sql, $link);
	
	//establim la data limit per a fer l'equip
	$sql = "UPDATE configuracio SET valor = '$limit' WHERE nom LIKE 'data_limit'";
	$result = mysql_query($sql, $link);

	//ara tenim que fer una copia dels jugadors de la jornada 0 a aquesta jornada
	$sql = "SELECT * FROM jugadors_elegits WHERE jornada = $JORNADA_BASE";
	$result = mysql_query($sql, $link);
	
	if (mysql_num_rows($result) > 0) {
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			$sql = "INSERT INTO jugadors_elegits VALUES(".$row["idConcursant"].",".$row["idJugador"].",$jornada,0)";
			$result2 = mysql_query($sql, $link);
		}
    }

    //Aliniacio de jose :)
    mysql_query("update jugadors_elegits set seleccionat = 1 where idConcursant = 12 and idJugdor in (1812,1845,1874,1947,1970,2091,2120,2123,2198,2230, 1872) and jornada = $jornada", $link);
}

//Comprova si el user i passwd son valids
function concursantValid($user, $passwd) {
	$link = conectar();
	$sql = "SELECT c.id FROM concursants c, mos_users u WHERE c.usuari = u.username AND c.usuari = '$user' AND u.password = '" . md5($passwd) . "'";
//	echo $sql;
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) == 1) {
		$row = mysql_fetch_array($result);
		return $row["id"];
	}
	return 0;
}

//torna el tipus de concursant: Registered, Super Usuari, Author, ...
function getUserType($u) {
	$link = conectar();
	
	$sql = "SELECT usertype FROM mos_users WHERE username LIKE '$u'";
	$result = mysql_query($sql, $link);
	$row = mysql_fetch_array($result);
	return $row["usertype"];
}

//comprova q la sessio esta iniciada
function conValidat() {
	global $_SESSION;
	if (isset($_SESSION["userid"]))
		return $_SESSION["userid"];
	return 0;
}

//comprova si es administraor
function esAdmin() {
	global $_SESSION, $ADMINISTRADOR;
	if (isset($_SESSION["userid"]) && $_SESSION["userid"] == $ADMINISTRADOR)
		return true;
	return false;
}

//tancar sessio
function tancarSessio() {
	global $_SESSION;
	unset($_SESSION["userid"]);
	header("Location: fer_equip.php");
}

//borra un jugador de la bbdd
function deleteJugador($jId) {
	$link = conectar();
	
	$ok = true;
	
	$sql = "SELECT * FROM jugadors_elegits WHERE idJugador = $jId";
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) > 0)	$ok = false;
	
	$sql = "SELECT * FROM punts_jornada WHERE idJugador = $jId";
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) > 0)	$ok = false;
		
	if ($ok) {
		$sql = "DELETE FROM jugadors WHERE id = $jId";
		$result = mysql_query($sql, $link);
	}
}


//duplica un jugador de la bbdd per a editarlo com a nou
function afegixJugador($jid,$eq) {
	$link = conectar();
	
	$ok = true;	
	
	$sql = "SELECT * FROM jugadors_elegits WHERE idJugador = $jId";
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) > 0)	$ok = false;
	
	$sql = "SELECT * FROM punts_jornada WHERE idJugador = $jId";
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) > 0)	$ok = false;
		
	if ($ok) {
		$sql2 = "SELECT max(id) from jugadors";
		$result2 = mysql_query($sql2, $link);
		$ultimId = NULL;	
		$ultimId = mysql_fetch_array($result2);
		$novaId = $ultimId[0] + 1;
				
		$sql11 = "SELECT nom, posicio, valor  from jugadors ";
        $sql11 .= "WHERE id = " . $jid;
		echo $sql11;
		$result11 = mysql_query($sql11, $link);
		echo $result11;
		$duplicar = NULL;
		$duplicar = mysql_fetch_array($result11);
		
		$nomast = substr($duplicar[0], 0, 3).'*****';  
				
		$sql = "INSERT INTO jugadors VALUES ($novaId,'$nomast',$eq,$duplicar[1],$duplicar[2],0)";
		
		$result = mysql_query($sql, $link);
		
	
	}
}


//update jugador
function updateJugador($id, $nom, $valor, $pos, $ecom) {
	$nom_utf8=utf8_decode($nom);
	$link = conectar();

	$sql = "UPDATE jugadors SET nom  = '$nom_utf8', valor = $valor, posicio = $pos, ecomunitari = $ecom WHERE id = $id";
	$result = mysql_query($sql, $link);
}

// //update jugador
// function updateJugador($id, $valor, $pos) {
	// $link = conectar();

	// $sql = "UPDATE jugadors SET valor = $valor, posicio = $pos WHERE id = $id";
	// $result = mysql_query($sql, $link);
// }

function updatePuntsJugador($id, $jornada, $punts) {
	$link = conectar();
	
	$sql = "SELECT * FROM punts_jornada WHERE idJugador = $id AND jornada = $jornada";
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) > 0)
		$sql = "UPDATE punts_jornada SET punts = $punts WHERE idJugador = $id AND jornada = $jornada";
	else
		$sql = "INSERT INTO punts_jornada VALUES ($id,$jornada,$punts)";
	$result = mysql_query($sql, $link);
}

//anula un jugador elegit
function anularJugador($con, $jug, $jor) {
	$link = conectar();

	$sql = "DELETE FROM jugadors_elegits WHERE idJugador = $jug AND idConcursant = $con AND jornada = $jor";
	$result = mysql_query($sql, $link);
}

//anyadix un enfrontament
function addEnfrontament($c1, $c2, $jornada) {
	$link = conectar();
	
	$sql = "INSERT INTO enfrontaments VALUES ($c1,$c2,$jornada)";
	$result = mysql_query($sql, $link);
}

//si la jornada te enfrontaments
function jornadaAmbEnfrontaments($jornada) {
	$link = conectar();
	
	$sql = "SELECT * FROM enfrontaments WHERE jornada = $jornada";
	$result = mysql_query($sql, $link);
	return mysql_num_rows($result);
}

//array amb els enfrontaments d'una jornada
function getEnfrontaments($jornada) {
	$link = conectar();
	
	$sql = "SELECT idJugador1, idJugador2 FROM enfrontaments WHERE jornada = $jornada";
	$result = mysql_query($sql, $link);

	$taula = null;
	if (mysql_num_rows($result)) {
			$i = 0;
			while($row = mysql_fetch_array($result)) {
					$info1 = getInfoConcursant($row["idJugador1"]);
					$info2 = getInfoConcursant($row["idJugador2"]);
					$taula[$i][0]["id"] = $info1["id"];
					$taula[$i][1]["id"] = $info2["id"];
					$taula[$i][0]["nom"] = $info1["nom"];
					$taula[$i][1]["nom"] = $info2["nom"];
					$punts1 = getPuntsPerJornadaYConcursant($jornada, $row["idJugador1"]);
					$punts2 = getPuntsPerJornadaYConcursant($jornada, $row["idJugador2"]);
					$taula[$i][0]["punts"] = $punts1["punts"]; 
					$taula[$i][1]["punts"] = $punts2["punts"];
					$i++;
			}
	}
	return $taula;

	
}

//torna el presupost gastat d'un concursant
function getPresupostGastat($id) {
	global $JORNADA_BASE;
	
	$link = conectar();
	
	$sql = "SELECT SUM(j.valor) as total FROM jugadors j, jugadors_elegits e WHERE j.id = e.idJugador AND e.idConcursant = $id AND e.jornada = $JORNADA_BASE";
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);
		return $row["total"];
	}
	return 0;
}

//trau els jugadors mes elegits
function getMesElegits() {
	$link = conectar();
	
	$sql = "SELECT j.nom, e.sigles, count(je.idConcursant) as num FROM jugadors j, jugadors_elegits je, equips e WHERE j.id = je.idJugador AND j.equip = e.id AND je.jornada = 0 GROUP BY je.idJugador ORDER BY num desc, nom LIMIT 60";
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

//trau el favorit d'un concursant
function getFavorit($cId) {
	$link = conectar();
	
	$sql = "SELECT c.usuari, j.nom, count(*) as num FROM concursants c, jugadors_elegits je, jugadors j";
	$sql .= " WHERE c.id = je.idConcursant AND j.id = je.idJugador AND je.seleccionat = 1 AND c.id = $cId AND je.jornada != 0";
	$sql .= " GROUP BY j.nom ORDER BY num desc LIMIT 1";
	$result = mysql_query($sql, $link);

	$row = null;
	if (mysql_num_rows($result) > 0)
		$row = mysql_fetch_array($result);
	return $row;	
}

//trau la alineacio titular per a una jornada
function getAliniacio ($c, $j) {
	$link = conectar();
	
	$sql = "SELECT j.id, j.nom, j.posicio, e.sigles FROM jugadors j, jugadors_elegits je, equips e";
	$sql .= " WHERE j.id = je.idJugador AND j.equip = e.id AND je.idConcursant = $c AND je.jornada = $j AND je.seleccionat = 1 ORDER BY j.posicio";
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

//trau els posibles jugadors estrella
function getPossiblesEstrelles() {
	$link = conectar();
	
	$sql = "SELECT  j.id, j.nom as nom_jug,j.ecomunitari as ecom, e.sigles, je.idConcursant as idC,count(je.idConcursant) as num FROM jugadors j, jugadors_elegits je, equips e WHERE j.id = je.idJugador AND j.equip = e.id AND je.jornada = 0 GROUP BY je.idJugador HAVING num = 1 ORDER BY num desc, idC";
	$result = mysql_query($sql, $link);

	$taula = null;
	if (mysql_num_rows($result)) {
			$i = 0;
			while($row = mysql_fetch_array($result)) {
					$taula[$i] = $row;
					$i++;
			}
	}
	
	for ($i = 0; $i < sizeof($taula); $i++) {
	//	$sql = "SELECT c.nom, c.nom_equip FROM concursants c, jugadors_elegits je WHERE c.id = je.idConcursant AND je.jornada = 0 AND je.idJugador = " . $taula[$i]["id"] ;
	$sql = "SELECT c.nom as nom_con, c.nom_equip FROM concursants c, jugadors_elegits je ";
	$sql .= "WHERE c.id = je.idConcursant AND je.jornada = 0 AND je.idJugador = " . $taula[$i]["id"] ;	
	//$sql .= "ORDER BY c.nom desc";

	
	
		$result = mysql_query($sql, $link);
		$row2 = mysql_fetch_array($result);
				// $taula[$i]["nom_con"] = $row["nom_con"];
		// $taula[$i]["nom_equip"] = $row["nom_equip"];
		$taula2[$i]["nom_con"] = $row2["nom_con"];
		$taula2[$i]["nom_equip"] = $row2["nom_equip"];
		$taula2[$i]["nom"] = $taula[$i]["nom_jug"];
		$taula2[$i]["sigles"] = $taula[$i]["sigles"];
		$taula2[$i]["ecom"] = $taula[$i]["ecom"];
	}

	//csort($taula2, "nom_con", SORT_ASC);
	
	return array_csort($taula2, "nom_con", SORT_ASC);
	
	
	return $taula2;	
}

//Torna el max i min de punts fets en una jornada
function getMaxMinPunts()
{
	$link = conectar();
	
	$jornadaActual = getJornadaActual();
	$resultat[0]["punts"] = -1000;
	$resultat[1]["punts"] = 1000;
	$resultat[0]["nom"] = '-';
	$resultat[1]["nom"] = '-';
	$resultat[0]["nom_equip"] = '-';
	$resultat[1]["nom_equip"] = '-';
	$resultat[0]["jornada"] = 0;
	$resultat[1]["jornada"] = 0;
	for ($i = 1; $i <= $jornadaActual; $i++) {
		$sql = "SELECT c.id, c.nom, c.nom_equip, SUM(p.punts) as punts";
		$sql .= " FROM concursants c, jugadors_elegits je, punts_jornada p";
		$sql .= " WHERE c.id = je.idConcursant AND je.idJugador = p.idJugador";
		$sql .= " AND je.jornada = $i AND je.jornada = p.jornada AND je.seleccionat = 1";
		$sql .= " GROUP BY c.id ORDER BY punts desc";

		$result = mysql_query($sql, $link);
	
		if (mysql_num_rows($result)) {
				while($row = mysql_fetch_array($result)) {
						if ($row["punts"] > $resultat[0]["punts"]) {
							$resultat[0]["punts"] = $row["punts"];
							$resultat[0]["nom"] = $row["nom"];
							$resultat[0]["nom_equip"] = $row["nom_equip"];
							$resultat[0]["jornada"] = $i;
						}
						if ($row["punts"] < $resultat[1]["punts"]) {
							$resultat[1]["punts"] = $row["punts"];
							$resultat[1]["nom"] = $row["nom"];
							$resultat[1]["nom_equip"] = $row["nom_equip"];
							$resultat[1]["jornada"] = $i;
						}
				}
		}
		
	}//for
	
	return $resultat;
}

function penalitzar($id_concursant, $punts, $motiu)
{
	$link = conectar();
	
	$sql = "SELECT sum(punts) from penalitzacio  where id_concursant  = $id_concursant  group by id_concursant";
	$result = mysql_query($sql, $link);
	
	

/*1  if ($row = mysql_fetch_array($result)){
		$punts_previs = $row["punts"];
		$punts_finals = $punts_previs - $punts;
		$sql = "UPDATE penalitzacio set punts = $punts_finals where id_concursant = $id_concursant";
		$result = mysql_query($sql, $link);
	}  
	else {*/ //Ho canvie a tot files noves i així guardarem el MOTIU de cada penalització en una fila nova
	
	
    $row = mysql_fetch_array($result);
		$punts_finals = 0 + $row[0] - $punts;
		$punts_in = 0 - $punts;
		$sql = "INSERT INTO penalitzacio ( id_concursant , punts , Motiu ) VALUES ( $id_concursant , $punts_in , '$motiu') "; 

		$result = mysql_query($sql, $link);

//}
		
	return "Actualment té $punts_finals punts.";
}

function getPenalitzacio($id_concursant)
{
        $link = conectar();

        $sql = "SELECT sum(punts) from penalitzacio where id_concursant = $id_concursant group by id_concursant";
        $result = mysql_query($sql, $link);
        $row = mysql_fetch_array($result);
        $punts_previs = $row[0];

	return $punts_previs;
}

function enviarAliniacions($jornada)
{
	$llistaCon = getTaula("concursants", "usuari");

	$salida = "Aliniacions de la jornada " . $jornada . "\n\n";

	for ($i = 0; $i < sizeof($llistaCon); $i++) {

		$salida .= "\n\n" . $llistaCon[$i]["usuari"] . ": ";

		$jugadors = getJugadorsPerPersona($llistaCon[$i]["id"], $jornada);
		for ($j = 0; $j < sizeof($jugadors); $j++) {
			if ($jugadors[$j]["seleccionat"] == 1)
				$salida .= $jugadors[$j]["nom"] . "(" . $jugadors[$j]["sigles"] . "); ";
		}
	}

        return $salida;

	//$estaEnviada = getValorConfiguracio("aliniacions_enviades");

	//if ($estaEnviada == "0")
	//{
		//Enviamos el mail
		//mail("jgargallo@gmail.com", "Aliniacions de la Jornada " + $jornada, "ALINIACIONS", "From: LFE <lfe@sebets.es>");

		//$link = conectar();
		//$sql = "UPDATE configuracio SET valor = '1' WHERE nom LIKE 'aliniacions_enviades'";
	    //$result = mysql_query($sql, $link);
	//}

}

function getNomJugador($id) {
	$link = conectar();
	$sql = "SELECT CONCAT(j.nom,' (',e.sigles,') - ', j.valor) as nombre";
	$sql .= " FROM jugadors j, equips e";
	$sql .= " WHERE j.equip = e.id and j.id = $id";

	$result  = mysql_query($sql, $link);
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		return $row["nombre"];
	}
	return null;
}

function getEstrategies() {
	$estr[0] = Array(3,4,3);
	$estr[1] = Array(4,4,2);
	$estr[2] = Array(6,3,1);
	$estr[3] = Array(5,4,1);
	$estr[4] = Array(4,5,1);
	$estr[5] = Array(3,6,1);
	$estr[6] = Array(4,3,3);
	$estr[7] = Array(5,3,2);
	$estr[8] = Array(3,5,2);

	return $estr;
}

//punts max que podies obtindre per a la jornada indicada
function getMaxPuntsJornada($idPersona, $jornada) {
	$estr = getEstrategies();

	$pMax = -100;
	for ($i = 0 ; $i < sizeof($estr) ; $i++) {
		$e = $estr[$i];
		$pPor = getPuntsMaximsPerPosicio($idPersona, $jornada, 1,1);
		$pDef = getPuntsMaximsPerPosicio($idPersona, $jornada, 2,$e[0]);
		$pMig = getPuntsMaximsPerPosicio($idPersona, $jornada, 3,$e[1]);
		$pDav = getPuntsMaximsPerPosicio($idPersona, $jornada, 4,$e[2]);
		$tot = $pPor + $pDef + $pMig + $pDav;
		if ($tot > $pMax) {
			$pMax = $tot;
		}
	}
	return $pMax;
}

//punts max que podies obtindre fins la jornada indicada
function getMaxPuntsTotals($idPersona, $ultJornada) {
	$tot = 0;
	for ($i = 1 ; $i <= $ultJornada ; $i++) {
		$tot = $tot + getMaxPuntsJornada($idPersona, $i);
	}

	return $tot;
}

function getClassificacioOptima() {
	$link = conectar();
	
	$resJornada = mysql_query("select valor from configuracio where nom = 'jornada_actual'", $link);
	$jornada = mysql_fetch_array($resJornada);
	$j = $jornada[0];

	$result = mysql_query("select id, nom, nom_equip from concursants", $link);
	$i = 0;
        while($row = mysql_fetch_array($result)) {
                $pTot = getMaxPuntsTotals($row[0], $j);
		$row[3] = $pTot;
		$taula[$i] = $row;
        	$i++;
        }
	
	csort($taula, 3, SORT_DESC);
	return $taula;
}

function getPuntsGeneralOptima()
{
        $link = conectar();
	$aCon = getTaula("concursants", "usuari");

	$resJornada = mysql_query("select valor from configuracio where nom = 'jornada_actual'", $link);
	$jo = mysql_fetch_array($resJornada);
	$jornada = $jo[0];
		
	for ($i = 0; $i < sizeof($aCon); $i++) {
		$puntsTotals = 0;
		$puntaverage = 0;
		$idCon = $aCon[$i]["id"];
		for ($j = 1; $j <= $jornada; $j++) {
			$punts = getMaxPuntsJornada($idCon, $j);
			$sql = "SELECT idJugador1, idJugador2 FROM enfrontaments WHERE jornada = $j";
			$sql .= " AND (idJugador1 = $idCon OR idJugador2 = $idCon)";
			$result = mysql_query($sql, $link);
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_array($result);
				if ($row["idJugador1"] == $idCon)
					$punts2 = getMaxPuntsJornada($row["idJugador2"], $j);					
				else
					$punts2 =  getMaxPuntsJornada($row["idJugador1"], $j);
				if ($punts > $punts2)
					$p = 3;
				else if ($punts == $punts2 && $punts2 != null)
					$p = 1;
				else
					$p = 0;
				$dif = $punts - $punts2;
			}
			else {
				$p = 0;
				$dif = 0;
			}
			$puntsTotals += $p;
			$puntaverage += $dif;
		}
		$aCon[$i]["punts"] = $puntsTotals;
		$aCon[$i]["puntaverage"] = $puntaverage;
	}
		
	//return csort($aCon, "punts", SORT_DESC);
	return array_csort($aCon, "punts", SORT_DESC, "puntaverage", SORT_DESC);
}

?>
