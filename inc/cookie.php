<?php
function checkcookies($db)
{
	if (!isset($_COOKIE["login"]) || !isset($_COOKIE["key"]) || !isset($_COOKIE["id"])) {
		return false;
	}

	$login=mysqli_real_escape_string($db, $_COOKIE["login"]);
	$key=mysqli_real_escape_string($db, $_COOKIE["key"]);
	$id=mysqli_real_escape_string($db, $_COOKIE["id"]);

	// Basic sanity checks first.
	if (empty($login)||empty($key)||empty($id)||($key=="deleted")||($id=="deleted")) {
		// Un-set cookies
		// Hack to deal with braindead browsers that don't unset properly.
		// Make sure cookie is full of garbage instead of the key.
		setcookie("key",md5("garbage")."x",time()-3600);
		setcookie("key","",time()-3600);
		setcookie("id","",time()-3600);
		return FALSE;
	} else {
		// Do some real checking.
		$result=mysqli_query($db, "select id, teststring, iv from user where name = \"$login\" and id = \"$id\"");
		if ($result != NULL && mysqli_num_rows($result) == 1) {
			$row=mysqli_fetch_row($result);
			if (testteststring(trim(decrypt($key,base64_decode($row[1]),base64_decode($row[2]))))) {
				$auth = new stdClass();
				$auth->login=$login;
				$auth->id=$id;
				$auth->key=$key;
				return $auth;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	// Just in case.
	return FALSE;
}

