<?php
include ("inc/cookie.php");
include ("inc/db.php");
include ("inc/form.php");
include ("inc/crypt.php");

$page="settings";

$db=sql_conn();

$auth=checkcookies($db);

if (!$auth) {
	header("Location: index.php");
	die();
}

$action=gorp("action",false);
$userid=$_COOKIE["id"];

$output="";


if ($action!==false) {
	switch ($action) {
		case "save":
			$catid=gorp("catid",0);
			$title=gorp("title","");
			if ($catid==0) {
				$query="insert cat values (NULL, \"$userid\", \"$title\")";
			} else {
				$query="update cat set title=\"$title\" where userid=\"$userid\" and id=\"$catid\"";
			}
			mysqli_query($db, $query);
			header("Location: ".$_SERVER["PHP_SELF"]);
			die();
		break;

		case "delete":
			// Check to see if this cat is used.
			$catid=gorp("catid",0);
			$result=mysqli_query($db, "select count(id) from logins where userid=\"$userid\" and catid=\"$catid\"");
			$row=mysqli_fetch_row($result);
			if ($row[0]>0) {
				header("Location: ".$_SERVER["PHP_SELF"]."?error=1");
			} else {
				mysqli_query($db, "delete from cat where id = \"$catid\" and userid=\"$userid\"");
				header("Location: ".$_SERVER["PHP_SELF"]);
				die();
			}
		break;

		case "edit":
			$catid=gorp("catid",0);
			if ($catid==0) {
				$title="";
			} else {
				$result=mysqli_query($db, "select title from cat where id = \"$catid\" and userid = \"$userid\"");
				$row=mysqli_fetch_assoc($result);
				$title=$row["title"];
			}

			$output.="<P CLASS=\"plain\">\n";
			$output.="Edit category:\n";
			$output.="<P CLASS=\"plain\">\n";
			$output.=form_begin($_SERVER["PHP_SELF"],"POST","settings");
			$output.=input_hidden("action","save");
			$output.=input_hidden("catid",$catid);
			$output.=input_text("title",30,255,$title);
			$output.=submit("Save");
			$output.=form_end();
		break;
	}
} else {
	$result=mysqli_query($db, "select id, title from cat where userid = \"$userid\"");
	$error=gorp("error","");

	if (!empty($error)) {
		$error="<SPAN CLASS=\"error\">Unable to delete. Remove entries from category first</SPAN>";
		$output.=$error."<P>\n";
	}

	$output.="<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"1\">\n";
	$output.="<TR>\n";
	$output.="<TD CLASS=\"header\" WIDTH=\"200\">Category</TD>\n";
	$output.="<TD CLASS=\"header\" WIDTH=\"150\">Action</TD>\n";
	$output.="</TR>\n";

	while ($row=mysqli_fetch_row($result)) {
		$output.="<TR><TD CLASS=\"row\">".$row[1]."</TD>\n";
		$output.="<TD CLASS=\"row\"><A HREF=\"".$_SERVER["PHP_SELF"]."?action=edit&catid=".$row[0]."\">Edit</A> | <A HREF=\"".$_SERVER["PHP_SELF"]."?action=delete&catid=".$row[0]."\">Delete</A></TD></TR>\n";
	}

	$output.="</TABLE>\n<P>\n";

	$output.=form_begin($_SERVER["PHP_SELF"],"POST");
	$output.=input_hidden("action","edit");
	$output.=input_hidden("catid","0");
	$output.=submit("New category");
	$output.=form_end();
}

include ("inc/header.php");

echo $output;

include ("inc/footer.php");

