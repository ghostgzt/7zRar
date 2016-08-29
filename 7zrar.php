<?php
/**
 * 7zRar For PHP
 * 
 * https://github.com/ghostgzt
 * 
 * Copyright 2014, Gentle Kwan
 * 
 * Licensed under MIT
 * 
 */
$debug=0;
if($debug){
	$action="x";
	$type="zip";
	$passwd="1234";
	$cps_file="test";
	$cps_dir="ddd";
	$dps_file="test.zip";//test.zip
	$dps_dir="d";
}else{
	$action=@$_GET["action"];
	$type=@$_GET["type"];
	$passwd=@$_GET["passwd"];
	$dps_file=@$_GET["file"];//test.zip
	$dps_dir=substr(@$dps_file,0,-(strlen(@end(@explode(".",@$dps_file)))+1));//output
	//die($dps_dir);
	$cps_dir=$dps_dir;
	$cps_file=$cps_dir;
}
$app7z=realpath(dirname(__FILE__)."/bin/7z");
$apprar=realpath(dirname(__FILE__)."/bin/rar");
 if(PATH_SEPARATOR == ':') {
	@chmod($app7z,01777);
	@chmod($apprar,01777);
}
//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
require("class_7zrar.php");
$c=new p7zrar();
echo "<style>html{font-family: Microsoft YaHei;text-shadow: 0px 0px 3px rgba(0, 0, 0, 0.5);}</style>7zRAR<br><hr><div style=\"padding-left:10px\">";
if(!$dps_file){
	echo "Command: action={a|x}&type={zip|rar|7z}&file={Archive Path}&passwd={}<br>";
}
if(!$action){
	echo(str_replace("\n","<br>",htmlspecialchars($c->tozip($dps_file,$dps_dir,$type,$passwd))));
}else{
	echo(str_replace("\n","<br>",htmlspecialchars($c->core($action,$dps_file,$dps_dir,$cps_file,$cps_dir,$type,$passwd))));
}
echo "</div><hr><div align=\"center\">Powered By Gentle</div>";