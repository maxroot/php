<?php require_once('../login/Connections/MySql.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  
  $logoutGoTo = "../login/login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
} 

$MM_restrictGoTo = "../login/login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  echo "<h1 style='font-family:Tahoma; font-size:30px;'>Acesso restrito <i>login para acessar essa pagina</h1>"; exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE login WHERE senha=%s",
                       GetSQLValueString($_POST['senha'], "text"));

  mysql_select_db($database_MySql, $MySql);
  $Result1 = mysql_query($updateSQL, $MySql) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE login SET senha=%s WHERE login=%s",
                       GetSQLValueString($_POST['text1'], "text"),
                       GetSQLValueString($_POST['text2'], "text"));

  mysql_select_db($database_MySql, $MySql);
  $Result1 = mysql_query($updateSQL, $MySql) or die(mysql_error());
}

mysql_select_db($database_MySql, $MySql);
$query_Login = "SELECT * FROM login";
$Login = mysql_query($query_Login, $MySql) or die(mysql_error());
$row_Login = mysql_fetch_assoc($Login);
$totalRows_Login = mysql_num_rows($Login);
?>
<?php
	error_reporting(0);

		if($_POST['comentar'] == ""){
			echo "<script>alert('Adicione o que deseja comentar!')</script>";
			echo '<meta http-equiv="refresh" content="0; url=index.php">';
			exit();
		}

	echo $nome = htmlspecialchars($_SESSION['MM_Username']);
	echo $comentario = htmlspecialchars($_POST['comentar']);
	if($_SESSION['MM_Username'] == "Max"){ //aqui pega essa frase no fwrite quando logar no Max
		$coments = fopen("comentarios.txt", "a");
		fwrite($coments, "<font color=green>[Dono]</font color=green> <b><font color=red><b>{$nome}</font color=red> :</b> {$comentario} <br><hr>");
	}
	
	   

	$coments = fopen("comentarios.txt", "a");
   if($coments != "Max") { //aqui era pra nao pegar quando eu logase no Max, soq aparece o outro ali e aqui . AS DUAS FRASES
   	fwrite($coments, "<font color=green>[Member]</font color=green> <b><font color=red><b>{$nome}</font color=red> :</b> {$comentario} <br><hr>");
   }


	fclose($coments);
	header("location: index.php");
	
?> 
<meta http-equiv="refresh" content="0;url=index.php">
