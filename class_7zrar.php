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
class p7zrar{
	function destroyDir($dir, $virtual = false) {
		$ds = DIRECTORY_SEPARATOR;
		$dir = $virtual ? realpath($dir) : $dir;
		$dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
		if (is_dir($dir) && $handle = opendir($dir)) {
			while ($file = readdir($handle)) {
				if ($file == '.' || $file == '..') {
					continue;
				}
				elseif(is_dir($dir.$ds.$file)) {
					$this->destroyDir($dir.$ds.$file);
				}
				else {
					unlink($dir.$ds.$file);
				}
			}
			closedir($handle);
			rmdir($dir);
			return true;
		}
		else {
			return false;
		}
	}
	function tozip($dps_file=null,$dps_dir=null,$intype=null,$passwd=null,$outtype="zip",$unz=null){
		if(!$dps_file||!$intype){
			return "Not Command!".PHP_EOL.shell_exec(realpath(dirname(__FILE__)."/bin/7z")).PHP_EOL.shell_exec(realpath(dirname(__FILE__)."/bin/rar"));
		}
		$c= $this->core("x",$dps_file,$dps_dir,null,null,$intype,$passwd);
		if(strstr(strtolower($c),"errors")||$unz){
			return $c;
		}
		$d= $this->core("a",null,null,$dps_dir,$dps_dir,$outtype,null);
		$e=	$this->destroyDir($dps_dir); 
		return $c.PHP_EOL.$d.PHP_EOL.($e?"Cleaned!":"Clean Failured!");
	}
	function core($action="a",$dps_file=null,$dps_dir=null,$cps_file=null,$cps_dir=null,$type=null,$passwd=null){
		@set_time_limit(0);
		@ignore_user_abort(true);		
		$app7z=realpath(dirname(__FILE__)."/bin/7z");
		$apprar=realpath(dirname(__FILE__)."/bin/rar");
		 if(PATH_SEPARATOR == ':') {
			@chmod($app7z,01777);
			@chmod($apprar,01777);
		}
		if(!$dps_file&&!$cps_file&&!$cps_dir){
			return "Not Command!".PHP_EOL."Not Command!".PHP_EOL.`$app7z`.PHP_EOL.`$apprar`;
		}
		$cps_dir=realpath($cps_dir);
		$zipcps="cd \"$cps_dir\" ".((PATH_SEPARATOR != ':')?("&& ".substr($cps_dir,0,2)." "):"")."&& \"".$app7z."\" a -r -y -tzip".($passwd?" -p".$passwd:"")." \"".realpath($cps_file).".zip\" *";
		$zipdps="\"".$app7z."\" x -y -tzip".($passwd?" -p".$passwd:"")." -o\"$dps_dir\" \"$dps_file\"";
		$rarcps="cd \"$cps_dir\" ".((PATH_SEPARATOR != ':')?("&& ".substr($cps_dir,0,2)." "):"")."&& \"".$apprar."\" a -r -y".($passwd?" -p".$passwd:"")." \"".realpath($cps_file).".rar\" *";
		$rardps="\"".$apprar."\" x -y".($passwd?" -p".$passwd:"")." \"$dps_file\" \"$dps_dir/\"";
		$p7zcps="cd \"$cps_dir\" ".((PATH_SEPARATOR != ':')?("&& ".substr($cps_dir,0,2)." "):"")."&& \"".$app7z."\" a -r -y -t7z".($passwd?" -p".$passwd:"")." \"".realpath($cps_file).".7z\" *";
		$p7zdps="\"".$app7z."\" x -y -t7z".($passwd?" -p".$passwd:"")." -o\"$dps_dir\" \"$dps_file\"";    
		switch ($type) {
			case '7z':
				if($action=="a"){
					@unlink($cps_file.".".$type);
					
					return `$p7zcps`;
				}else{
				//die($p7zdps);
					return `$p7zdps`;
				}
				break;
			case 'rar':
				if($action=="a"){
					@unlink($cps_file.".".$type);			
						
					return `$rarcps`;
				}else{
					@mkdir($dps_dir);
					return `$rardps`;
				}
				break;        
			default:
				if($action=="a"){
					@unlink($cps_file.".".$type);		
					//die($zipcps);
					return `$zipcps`;
				}else{
					return `$zipdps`;
				}
				break;
		}
	}
}