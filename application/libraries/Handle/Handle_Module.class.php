<?php

Class Handle_Module_Class{
	static function validate_method($method){
		$valid_methods = array(
													'view',
													'form',
													'update',
													'insert',
													'ajax'
													);
	$valid = false;
	foreach($valid_methods as $valid_method){
			if($valid_method == $method){
				$valid = true;
			}
	}										
		return $valid;			
	}
	
	static function create_nonce($dataobject,$salt,$id = null){
		$nonce = md5($dataobject.''.$salt.''.$id);
		return $nonce;
	}
			
	static function format_array($arr,$id,$title){
		$res = array();
		foreach($arr as $key => $value){
			$res[$value[$id]] = $value[$title];
		}
		return $res;
	}
			
	static function form_open($path,$attr,$form = null){
		echo form_open($path,$attr);
		if($form != null)
		{
			foreach($form->element as $element){
				if(strpos($element['input'],'post_action') != false){
					echo $element['input'];
				}
			}
		}
	}
}