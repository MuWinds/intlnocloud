<?php
namespace lib;

class PayTemplate {

	static public function getList(){
		$dir = PAYTEMPLATE_ROOT;
		$dirArray[] = NULL;
		if (false != ($handle = opendir($dir))) {
			$i = 0;
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && strpos($file, ".")===false) {
					$dirArray[$i] = $file;
					$i++;
				}
			}
			closedir($handle);
		}
		return $dirArray;
	}

	static public function load($name = 'INTL_Pay'){
		global $userrow;
		$template = $userrow['pay_template']?$userrow['pay_template']:'default';
		//if(!preg_match('/^[a-zA-Z0-9]+$/',$name))exit('error');
		$filename = PAYTEMPLATE_ROOT.$template.'/'.$name.'.php';
		$filename_default = PAYTEMPLATE_ROOT.'default/'.$name.'.php';
		if(file_exists($filename)){
			define("PAYINDEX_ROOT",PAYTEMPLATE_ROOT.$template.'/');
			define("PAYSTATIC_ROOT",'/Submit/Template/'.$template.'/assets/');
			return $filename;
		}elseif(file_exists($filename_default)){
			define("PAYINDEX_ROOT",PAYTEMPLATE_ROOT.'default/');
			define("PAYSTATIC_ROOT",'/Submit/Template/default/assets/');
			return $filename_default;
		}else{
			exit('PayTemplate file not found');
		}
	}

	static public function exists($template){
		$filename = PAYTEMPLATE_ROOT.$template.'/INTL_pay.php';
		if(file_exists($filename)){
			return true;
		}else{
			return false;
		}
	}
}
