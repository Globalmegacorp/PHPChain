<?php
define ('C_VERSION','1.1');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                           

if ($_SERVER["HTTPS"]!="on") {
	$warning="SSL not enabled! Connection is not secure! Click <A HREF=\"https://".$_SERVER["HTTP_HOST"].$PHP_SELF."\">here</A> for secure version.";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
    "http://www.w3.org/TR/REC-html40/loose.dtd">
<HTML>
<HEAD>
<LINK REL=StyleSheet HREF="style.css" TYPE="text/css">
<TITLE>PHPChain</TITLE>
<script>
var inactivityTime = function () {
    var time;
    // DOM Events
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onload = resetTimer;
    document.onmousedown = resetTimer; // touchscreen presses
    document.ontouchstart = resetTimer;
    document.onclick = resetTimer;     // touchpad clicks
    document.onscroll = resetTimer;    // scrolling with arrow keys

    function logout() {
        location.href = 'logout.php?idle=1'
    }

    function resetTimer() {
      clearTimeout(time);
      time = setTimeout(logout, 10*60*1000); // 1000 milliseconds = 1 second
    }
};
inactivityTime();
</script>
</HEAD>
<BODY CLASS="main">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
<TR>
<TD COLSPAN="2" CLASS="titlebar" onClick="javascript:document.location='index.php'">
PHPChain <SPAN CLASS="plain"> - Powered by <A HREF="http://www.globalmegacorp.org/PHPChain">PHPChain <?php echo C_VERSION; ?></A></SPAN>
</TD>
</TR>
<TR>
<TD COLSPAN="2" CLASS="menubar" WIDTH="100%">
<?php
$left="";
$right="";

if ($auth) {
	$left.="<A HREF=\"settings.php\" CLASS=\"menubar\">Settings</A>";
	$left.="<A HREF=\"password.php\" CLASS=\"menubar\">Password</A>";
        $left.="<A HREF=\"search.php\" CLASS=\"menubar\">Search</A>";	
	$left.="<A HREF=\"logout.php\" CLASS=\"menubar\">Logout</A>";
	$right.="Logged in as: ".$auth->login;
} else {
	$left.="<A HREF=\"newlogin.php\" CLASS=\"menubar\">Create login</A>";
	$right.=form_begin("login.php","POST");
	$right.="Login: ".input_text("login",8,255,"","login");
	$right.="&nbsp;Password: ".input_passwd("key",8,255,NULL,"login")."&nbsp;";
	$right.=submit("Login",NULL,"submit");
	$right.=form_end();
}

$menu="<SPAN CLASS=\"menuleft\">".$left."</SPAN><SPAN CLASS=\"menuright\">".$right."</SPAN>";
echo $menu;

?>
</TD>
</TR>
<?php
if (isset($warning)) {
	echo "<TR><TD COLSPAN=\"2\" CLASS=\"nossl\" WIDTH=\"100%\">".$warning."</TD></TR>";
}
?>
<TR>
<TD WIDTH="120" VALIGN="TOP">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%"> <!-- menu wrapper table -->
<TR><TD WIDTH="100%" CLASS="cats">&nbsp;</TD></TR>
<?php
if ($auth) {
	include ("inc/menu.php");
        $catid="none";
        if ($page=="cat") {
               $catid=gorp($db, "catid","");
        }
	echo getmenu($auth->id,$catid);
}
?>
<TR>
<TD WIDTH="100%" CLASS="catsbot">
<?php
if ($auth) {
	echo form_begin("cat.php","POST");
	echo input_hidden("action","edit");
	echo input_hidden("itemid",0);
	echo input_hidden("catid",$catid);
	echo submit("New entry");
	echo form_end();
}
?>
<IMG SRC="img/tiny.gif" WIDTH="120" HEIGHT="1">
</TD></TR></TABLE> <!-- End menu wrapping table -->

</TD>
<TD WIDTH="100%" CLASS="main">
