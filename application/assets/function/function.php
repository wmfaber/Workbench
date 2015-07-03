<?php
function pr($term,$exit = false){
		echo "<pre>";
		echo "<div style='background-color:#768CEE'>";
		print_r($term);
		echo "</div>";
		echo "</pre>";
		if($exit){
			exit;
		}
}

function get_active_modules(){
	$res= array();
	$control_dir = getcwd().'/application/controllers/';
	if ($handle = opendir($control_dir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
        	if(is_dir($control_dir.$entry)){
        		$res[$entry] = parse_ini_file($control_dir.$entry.'/Config.ini');
        	}elseif(strpos($entry,'.ini')){
        		//$res['Home'] = parse_ini_file($control_dir.'/Config.ini');
        	}
        }
    }
    closedir($handle);
	}
	return $res;	
}



function get_database_to_formfield($field,$module= false){
		switch($field){
			case "desc":
			$res = 'editor';
			break;
			case "id":
			$res = 'hidden';
			break;
			case "typeahead":
			$res = $res;
			break;
			case "status_id":
			$res = 'select';
			break;
			case "require_login":
			case "exported":
			case "active":
			$res = 'checkbox';
			break;
			default:
			$res = 'text';
			break;
	}
	return $res;
} 

	function clean_databasefield($field){
		if (strpos($field,"(") !== false) {
				$field = str_replace("(" , ",", $field);
				$field = explode(',' , $field);
				$field = $field[0];
		}
		return $field;
	}
	