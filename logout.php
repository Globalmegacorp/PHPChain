<?php
// Un-set cookies
// Hack to deal with braindead browsers which don't unset cookies.
setcookie("key",md5("garbage")."x",time()-3600);
setcookie("key","",time()-3600);
setcookie("id","",time()-3600);
$idle="";
if(isset($_GET["idle"])) { 
	$idle="?idle=1";
}
header("Location: index.php".$idle);

