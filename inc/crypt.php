<?php
function make_iv()
{
	srand ((float) microtime() * 10000000);
	return mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_CBC), MCRYPT_RAND);
}

function encrypt ($key, $data, $iv)
{
	return mcrypt_encrypt (MCRYPT_BLOWFISH, $key, $data, MCRYPT_MODE_CBC, $iv);
}

function decrypt ($key, $data, $iv)
{
	return mcrypt_decrypt (MCRYPT_BLOWFISH, $key, $data, MCRYPT_MODE_CBC, $iv);
}

function maketeststring ()
{
	$s="";
	srand((double)microtime()*1000000);
	for ($i=0;$i<12;$i++) {
		$s.=chr(rand(48,122));
	}
	return $s.$s;
}

function testteststring ($teststring)
{
	if (substr($teststring,0,12)==substr($teststring,12,12)) {
		return TRUE;
	} else {
		return FALSE;
	}
}


