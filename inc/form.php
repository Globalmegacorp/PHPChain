<?php
function form_begin ($action, $method, $id="", $enctype="")
{
	return "<FORM ACTION=\"$action\" METHOD=\"$method\" ENCTYPE=\"$enctype\" ID=\"".$id."\">\n";
}

function form_end ()
{
	return "</FORM>";
}

function input_text ($name, $size, $maxlength, $value=NULL, $style="plain")
{
	return "<INPUT TYPE=\"TEXT\" NAME=\"".$name."\" SIZE=\"$size\" MAXLENGTH=\"$maxlength\" VALUE=\"".$value."\" CLASS=\"".$style."\">\n";
}

function input_file ($name, $size, $style="plain")
{
	return "<INPUT TYPE=\"FILE\" NAME=\"".$name."\" SIZE=\"$size\" CLASS=\"".$style."\">\n";
}

function input_passwd ($name, $size, $maxlength, $value=NULL, $style="plain")
{
	return "<INPUT TYPE=\"PASSWORD\" NAME=\"".$name."\" SIZE=\"$size\" MAXLENGTH=\"$maxlength\" VALUE=\"".$value."\" CLASS=\"".$style."\">\n";
}

function input_hidden ($name, $value)
{
	return "<INPUT TYPE=\"HIDDEN\" NAME=\"".$name."\" VALUE=\"".$value."\">\n";
}



function input_radio ($name, $value, $checked=FALSE, $style="plain")
{
	if ($checked) {
		return "<INPUT TYPE=\"RADIO\" NAME=\"".$name."\" VALUE=\"".$value."\" CLASS=\"".$style."\" CHECKED>\n";
	} else {
		return "<INPUT TYPE=\"RADIO\" NAME=\"".$name."\" VALUE=\"".$value."\" CLASS=\"".$style."\">\n";
	}
}

function input_select ($name, $default, $data, $style="plain")
{
	$output="<SELECT NAME=\"".$name."\" CLASS=\"".$style."\">\n";

	while (list(,$value) = each($data)) {
		if ($value[0]==$default) {
			$output .= "<OPTION SELECTED VALUE=\"".$value[0]."\">".$value[1]."</OPTION>\n";
		} else {
			$output .= "<OPTION VALUE=\"".$value[0]."\">".$value[1]."</OPTION>\n";
		}
	}
	$output .= "</SELECT>\n";

	return $output;
}

function input_multiselect ($name, $id, $default=array(0), $data, $rows, $style="plain")
{
	$output="<SELECT MULTIPLE NAME=\"".$name."\" ID=\"".$id."\" SIZE=\"$rows\" CLASS=\"".$style."\" STYLE=\"".$style."\">\n";

	while (list(,$value) = each($data)) {
		
		if (in_array($value[0],$default)) {
			$output .= "<OPTION VALUE=\"".$value[0]."\" SELECTED>".$value[1]."</OPTION>\n";
		} else {
			$output .= "<OPTION VALUE=\"".$value[0]."\">".$value[1]."</OPTION>\n";
		}
	}
	$output .= "</SELECT>\n";

	return $output;
}

function textarea ($name, $text, $rows, $cols, $style="plain")
{
	$output.="<TEXTAREA NAME=\"$name\" ROWS=\"$rows\" COLS=\"$cols\" CLASS=\"$style\">";
	$output.=$text;
	$output.="</TEXTAREA>\n";

	return $output;
}

function submit ($name, $onclick="", $style="plain")
{
	return "<INPUT TYPE=\"SUBMIT\" CLASS=\"$style\" VALUE=\"$name\" onClick=\"".$onclick."\">\n";
}

function input_button ($name, $onclick="", $style="plain")
{
	return "<INPUT TYPE=\"BUTTON\" CLASS=\"$style\" VALUE=\"$name\" onClick=\"".$onclick."\">\n";
}
function gorp($db, $fieldname, $default)
{
	if (strtolower($_SERVER["REQUEST_METHOD"])=="get") {
		if(isset($_GET["$fieldname"])) return mysqli_real_escape_string($db, $_GET["$fieldname"]);
	} else if (strtolower($_SERVER["REQUEST_METHOD"])=="post") {
		if(isset($_POST["$fieldname"])) return mysqli_real_escape_string($db, $_POST["$fieldname"]);
	}
	return $default;
}


