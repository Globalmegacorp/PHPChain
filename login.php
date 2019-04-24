<?php
include ("inc/db.php");
include ("inc/form.php");
include ("inc/crypt.php");

$db=sql_conn();

$login=mysqli_real_escape_string($db, $_POST["login"]);
$key=mysqli_real_escape_string($db, $_POST["key"]);

if (empty($login)) {
	$login=mysqli_real_escape_string($db, $_COOKIE["login"]);
	if (empty($login)) unset ($login);
}
if (empty($key)) unset ($key);

if (!isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
	$ip=$_SERVER["REMOTE_ADDR"];
} else {
	$ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
}

$output="";
$error="";

if (isset($login)&&isset($key)) {
	// Check for failed login attempts in the last 10 minutes.
	$result=mysqli_query($db, "select count(name) from loginlog where date > date_sub(now(), INTERVAL 10 minute) and ip = \"$ip\" and outcome = 0");
	$row=mysqli_fetch_row($result);
	if ($row[0]<3) {
		$result=mysqli_query($db, "select id, teststring, iv from user where name = \"$login\"");
		if (mysqli_num_rows($result) == 1) {
			$row=mysqli_fetch_row($result);
			$key=md5($key);
			if (testteststring(trim(decrypt($key,base64_decode($row[1]),base64_decode($row[2]))))) {
				// Login log
				mysqli_query($db, "insert loginlog values (\"$login\", \"$ip\", now(),1)");
				setcookie("login",$login,time()+(3600*24*365));
				setcookie("id",$row[0],time()+(3600*24*365));
				setcookie("key",$key,0);
				header ("Location: index.php");
				die();
			} else {
				$error="<SPAN CLASS=\"error\">Login failed</SPAN>\n";
			}
		} else {
			$error="<SPAN CLASS=\"error\">Login failed</SPAN>\n";
		}
	} else {
		$error="<SPAN CLASS=\"error\">Too many failed login attempts. Wait 10 minutes.</SPAN>\n";
	}
}

if (!empty($error)) {
	// Make entry to login log table
	mysqli_query($db,"insert loginlog values (\"$login\", \"$ip\", now(),0)");
}

$output.=form_begin($_SERVER["PHP_SELF"],"POST");
$output.="<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"0\">\n";
$output.="<TR><TD CLASS=\"plain\" COLSPAN=\"2\">".$error."</TD></TR>\n";
$output.="<TR><TD CLASS=\"plain\" COLSPAN=\"2\">&nbsp;&nbsp;</TD></TR>\n";
$output.="<TR><TD CLASS=\"plain\">Login: </TD><TD CLASS=\"plain\">".input_text("login",30,255,$login)."</TD></TR>\n";
$output.="<TR><TD CLASS=\"plain\">Password: </TD><TD CLASS=\"plain\">".input_passwd("key",30,255)."</TD></TR>\n";
$output.="<TR><TD CLASS=\"plain\" COLSPAN=\"2\" ALIGN=\"RIGHT\">".submit("Login")."</TD></TR>\n";
$output.="</TABLE>\n";
$output.=form_end();

include("inc/header.php");
echo $output;
include("inc/footer.php");


