<?php
include ("inc/cookie.php");
include ("inc/db.php");
include ("inc/form.php");
include ("inc/crypt.php");

$page="search";

$db=sql_conn();

$auth=checkcookies($db);

if (!$auth) {
    header("Location: logout.php");
    die();
}

$search_string=strtolower(gorp($db,"search_string",""));

$output="<P CLASS=\"plain\">";
$found=FALSE;

if (!empty($search_string)) {
	$result=mysqli_query($db, "select l.id, l.iv, l.login, l.password, l.site, l.url, c.title from logins l join cat c on l.catid = c.id where l.userid = \"".$auth->id."\"");
	if (mysqli_num_rows($result)==0) {
		$output.="<SPAN CLASS=\"plain\">No data found</SPAN>";
	} else {
		$resarray=array();

		while ($row=mysqli_fetch_assoc($result)) {
		    $login=trim(decrypt($auth->key,base64_decode($row["login"]),base64_decode($row["iv"])));
		    $password=trim(decrypt($auth->key,base64_decode($row["password"]),base64_decode($row["iv"])));
		    $site=trim(decrypt($auth->key,base64_decode($row["site"]),base64_decode($row["iv"])));
		    $url=trim(decrypt($auth->key,base64_decode($row["url"]),base64_decode($row["iv"])));

		    if (strpos(strtolower($login),$search_string) !== false ||
			    strpos(strtolower($password),$search_string) !== false ||
			    strpos(strtolower($site),$search_string) !== false ||
			    strpos(strtolower($url),$search_string) !== false) {
				    $resarray[]=array("id"=>$row["id"], "category"=>$row["title"],  "login"=>$login, "password"=>$password, "site"=>$site, "url"=>$url);
				    $sortarray[]=$row["login"];
		    }
		}

		if (count($resarray)==0) {
			$output.="<SPAN CLASS=\"plain\">No data found</SPAN>";
		} else {
			array_multisort($sortarray, SORT_ASC, $resarray);

			$output.="<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"1\">\n";
			$output.="<TR>\n";
			$output.="<TD CLASS=\"header\" WIDTH=\"80\">Category</TD>\n";
			$output.="<TD CLASS=\"header\" WIDTH=\"300\">Site</TD>\n";
			$output.="<TD CLASS=\"header\" WIDTH=\"200\">Login</TD>\n";
			$output.="<TD CLASS=\"header\" WIDTH=\"120\">Password</TD>\n";
			$output.="</TR>\n";

			foreach ($resarray as $val) {
				if (strlen($val["url"])>1) {
					$outsite="<A HREF=\"".$val["url"]."\" TARGET=\"_blank\">".$val["site"]."</A>";
				} else {
					$outsite=$val["site"];
				}
				$output.="<TR>";
				$output.="<TD CLASS=\"row\">".$val["category"]."</TD>\n";
				$output.="<TD CLASS=\"row\">".$outsite."</TD>\n";
				$output.="<TD CLASS=\"row\">".$val["login"]."</TD>\n";
				$output.="<TD OnMouseOver=\"this.style.color='#000000'\" OnMouseOut=\"this.style.color='#fdfed0'\" CLASS=\"password\">".$val["password"]."</TD>\n";
				$output.="</TR>";
			}
			$output.="</TABLE>\n";
		}
    	}
}

$output.="<P>";
$output.=form_begin($_SERVER["PHP_SELF"],"POST");
$output.="<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"0\">\n";
$output.="<TR><TD CLASS=\"plain\">Search: </TD><TD CLASS=\"plain\">".input_text("search_string",20,255)."</TD></TR>\n";
$output.="<TR><TD CLASS=\"plain\" COLSPAN=\"2\" ALIGN=\"RIGHT\">".submit("Search")."</TD></TR>\n";
$output.="</TABLE>\n";
$output.=form_end();

include ("inc/header.php");

echo $output;

include ("inc/footer.php");
?>
