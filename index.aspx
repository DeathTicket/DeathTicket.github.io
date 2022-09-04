<?php
/*
    Created by aqil.almara
*/

@set_time_limit(0);
// @error_reporting(E_ALL^(E_NOTICE|E_WARNING));

function printn($value="", $end="<br />") {
    if (is_array($value)):
        $value = json_encode($value, JSON_PRETTY_PRINT);
        $value = str_replace("    ", "&nbsp;&nbsp;&nbsp;&nbsp;", $value);
        return(print(str_replace("\n", $end, $value)));
    elseif (is_array(json_decode($value, 1))):
        return(printn(json_decode($value, 1), $end));
    endif;
    return(print(str_replace("\n", $end, "{$value}{$end}")));
}

function cp($p) {
    if (is_dir($p)) {
        $x = DIRECTORY_SEPARATOR;
        while (substr($p,-1)==$x) {
            $p = rtrim($p, $x);
        } return($p.$x);
    } return($p);
}
function Get_media($pwd='') {
	if (!$pwd) {$pwd = cp(getcwd());}
    if (is_dir($pwd)) {
        $pwd = cp($pwd);
        chdir($pwd);
    }

	$_dtb__ = array();

    foreach (scandir($pwd) as $file) {
        if (is_file($file)) {
            $filn = substr(basename($file), -3, 3);
            if ($filn!="php") {
                $_dtb__[] = $file;
            }
        }
        elseif (is_dir($file)) {
        	if ($file!="." and $file!="..") {
        		$_dtb__[$file] = Get_media($pwd.$file);
        		chdir($pwd);
        	}
        }
    }
    // sort($_dtb__, SORT_NATURAL|SORT_FLAG_CASE);
    return($_dtb__);
}

printn(Get_media());
?>
<style type="text/css">
* {
    background: #37474F;
    padding: 30px 0px;
    color: #dddddd;
    font-size: 1.1em;
    line-height: 1.3em;
    font-family: monospace;
}
</style>
