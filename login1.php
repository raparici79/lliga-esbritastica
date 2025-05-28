<?php
/************************************************
*	File: 	login1.php					*
*	Desc: 	login
*	Author:	Jose Gargallo 						*
************************************************/
session_start();
include("config.php");
include("funcions.php");

?>
<?php
$msg = "";
$laUrl = $_GET["url"];
$esPrivado = $laUrl == "ini_jornada.php" || $laUrl == "edit_jugadors.php" || $laUrl == "edit_punts.php" || $laUrl == "edit_enfrontaments.php" || $laUrl == "penalitzar.php"; 
if (isset($_POST["username"]) && $_POST["username"] != "") { //iniciem la sessio

	if ($conId = concursantValid($_POST["username"], $_POST["passwd"])) {
	    if (!$esPrivado 
		|| strtoupper($_POST["username"]) == "ESBRI" 
		|| strtoupper($_POST["username"]) == "RUBEN"
		|| strtoupper($_POST["username"]) == "RAFA"
		|| strtoupper($_POST["username"]) == "JAVI"
		|| strtoupper($_POST["username"]) == "XTUR")
	    {
		$_SESSION["userid"] = $conId;
		$_SESSION["usertype"] = getUserType($_POST["username"]);
		header("Location: " . $_GET["url"]);
	    }
	    else
		$msg = "<font color=\"#ff0000\">No tens permís!!!</font>";
	}
	else
		$msg = "<font color=\"#ff0000\">Les dades no són correctes</font>";

}

if (isset($_GET["tancar"]))
	tancarSessio();

?>

<!DOCTYPE html>    
<html>    
<head>    

	<style>
		body  {  
		margin: 0;  
		padding: 0;  
					background-image:url(images/FONDO_LOGIN8.jpg);
		
		/* Fijar la imagen de fondo este vertical y
		horizontalmente y centrado */
	  background-position: center center;

	  /* Esta imagen no debe de repetirse */
	  background-repeat: no-repeat;

	  /* COn esta regla fijamos la imagen en la pantalla. */
	  background-attachment: fixed;

	  /* La imagen ocupa el 100% y se reescala */
	  background-size: 100% 100%;

	  /* Damos un color de fondo mientras la imagen está cargando  */
	  background-color: #000000;

		font-family: 'Arial';  
	}  
	.login{  
			width: 80%;  
			overflow: hidden;  
			margin-top: 1px;
			text-align: center;
			background: black;
			border-radius: 15px ;  
			position:absolute;
			left: 10%;
			bottom: 10px;
		
			  
	}  

	label{  
		color: grey;  
		font-size: 17px;  
	}  
	#Uname{  
		width: 80%;  
		height: 30px;  
		border: none;  
		border-radius: 10px;  
		text-align: center;
	}  
	#Pass{  
		width: 80%;  
		height: 30px;  
		border: none;  
		border-radius: 10px;  
		text-align: center;
		  
	}  
	#log{  
		width: 80%px;  
		height: 30px;  
		border: none;  
		border-radius: 17px;  
		padding-left: 7px;  
		color: black;  
		font-weight: bold;
	  
	  
	}  
	span{  
		color: white;  
		font-size: 17px;  
	}  
	a{  
		float: right;  
		background-color: grey;  
	}  
	
	a:link, a:visited {
	color: blck; text-decoration: none;
	font-weight: bold;
}


	</style>
</head>    
<body>    
    <div class="login">        
	<form id="login" action="login1.php?url=<?= $_GET["url"] ?>" method="post">
	
        <label><b>Usuari</b></label>    <br>
    <!--<input type="text" name="Uname" id="Uname" placeholder="Username">   -->
		<input id="Uname" name="username" type="text" class="inputbox" alt="username" />		
        <br><br>    
        <label><b>Password</b></label>    <br>
    <!--<input type="Password" name="Pass" id="Pass" placeholder="Password">    -->
		<input id="Pass" type="password" name="passwd" class="inputbox" alt="password" />
        <br><br>    
    <!--<input type="button" name="log" id="log" value="ENTRAR">     -->
		<input id="log" type="submit" value="ENTRAR">
        <br>
    </form>    
	<?= $msg ?>
</div>    
</body>    
</html>     