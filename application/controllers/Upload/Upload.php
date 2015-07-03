<?php
require_once(getcwd().'/application/libraries/Handle/Handle_Module.php');
require_once('Mascot.class.php');

Class Upload extends Handle_Module {

	public function start_module(){
		$active_modules = get_active_modules();
		if(isset($active_modules[$this->module])){
			$this->settings = $active_modules[$this->module];
		
			if($this->settings['module']['require_login'] != true || $this->session->userdata('username') != null)
			{
				$this->dataobject = strtolower($this->settings['module']['dataobject']);
				$this->data['vendor_dropdown'] = $this->get_drop_down("Vendor");
			}
			else{
				redirect('login');
			}
		}
	}
	
	public function get_drop_down($name, $default=null){
		$this->db->select(array('id','title','desc'));
		$this->db->where(array('active'=>'Y'));
		
		$result = $this->db->get('vendor')->result_array();
		
		$dropdown = "<select name='".$name."' class='form-control input-xlarge'>";
		foreach($result as $value)
		{
				$dropdown .= "<option value='".$value['desc']."' >".$value['title']."</option>";
		}
		$dropdown .= "</select>";
		return $dropdown;
	}
	
	public function import($data = null){
		$error = array();
		if ($data != null){
			$object = array();
			$file = $data['upload_data']['full_path'];
			$this->load->library('Excel');

			$objPHPExcel = PHPExcel_IOFactory::load($file);

			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			
			foreach ($cell_collection as $cell) {
				$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
				$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();

				if (PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getCell($cell))){
					$objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->getFormatCode();
					$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getFormattedValue();
				}
				else{
					$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
				}
				//////// SET KOOPJEDEAL HEADERS /////////
				if ($data['upload_data']['orig_name'] == "koopjedeal.xlsx")
				{
						$header[1]['A'] = 'vendor_order_id';
						$header[1]['B'] = 'naam';
						$header[1]['C'] = 'adres';
						$header[1]['D'] = 'plaats';
						$header[1]['E'] = 'postcode';
						$header[1]['F'] = 'land';
						$header[1]['G'] = 'product';
				}
				if ($row == 1) {
					$header[$row][$column] = $data_value;
					////// SET PRODUCT FOR SHEDEALS ////////
					if ($data['upload_data']['orig_name'] == "shedeals.xlsx") {
						$header[$row]['A'] = "Product";
					}
				} 
				else 
				{
					if (isset($header[1][$column]))
					$arr_data[$row][$header[1][$column]] = $data_value;
				}
			}
			
			$import['name']   = $data['file_name'];
			$import['values'] = $arr_data;
			$import = array_filter($import);
			$import['values'] = $this->filter_speciaal_characters($import['values']);
			
			switch(strtolower($import['name'])) {
				case "6deals":
				$object = Mascot_Class::insert_6deals($import);
				break;
				case "1dayfly":
				$object = Mascot_Class::insert_1dayfly($import);
				break;
				case "actievandedag":
				$object = Mascot_Class::insert_actievandedag($import);
				break;
				case "groupdel":
				$object = Mascot_Class::insert_groupdel($import);
				break;
				case "groupon":
				$object = Mascot_Class::insert_groupon($import);
				break;
				case "ichica":
				$object = Mascot_Class::insert_ichica($import);
				break;
				case "marktplaats":
				$object = Mascot_Class::insert_marktplaats($import);
				break;
				case "onedayonly":
				$object = Mascot_Class::insert_onedayonly($import);
				break;
				case "shedeals":
				$object = Mascot_Class::insert_shedeals($import);
				break;
				case "wegener":
				$object = Mascot_Class::insert_wegener($import);
				break;
				case "sweetdeals":
				$object = Mascot_Class::insert_sweetdeals($import);
				//$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Encoding is niet juist!</div>');
				//redirect($this->module.'/upload_form');
				break;
				case "ticketveilingen":
				$object = Mascot_Class::insert_ticketveilingen($import);
				break;
				case "vakantieveilingen":
				$object = Mascot_Class::insert_vakantieveilingen($import);
				break;
				case "koopjedeal":
				$object = Mascot_Class::insert_koopjedeal($import);
				break;
				case "voordeelvanger":
					$object = Mascot_Class::insert_voordeelvanger($import);
				break;
				case "nalevering":
					$object = Mascot_Class::insert_nalevering($import);
				break;
			}
			if($object != null) {
				$row = 0;
				foreach ($object as $array) {
					if($this->validate_array($array)){
						$product = $this->get_product_by_title($array['product']);
						$array['product_id'] = $product['id'];
						unset($array['product']);
						$this->foreach_insert($array);
					}
					else
					{
						array_push($error, 'Verificatie error object is niet juist: '.$row);
					}
					$row++;
				}
				if(!empty($error))
				{
					$error_message = "";
					foreach ($error as $value){
						$error_message .= $value."<br />";
					}
					$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">'.$error_message.'</div>');
				}
			}
			else
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Type bestand niet gevonden: '.$import['name'].'</div>');
				redirect($this->module.'/upload_form');
			}
		}
		return true;
	}

	public function import_array($set){
		return $import = array('upload_data' => $set, 'file_name' => $this->input->post('Vendor'));
	}
	
	public function validate_array($array){
		$valid = true;

		if(
		$array['product'] == '' or
		$array['plaats'] == '' or
		$array['adres'] == '' or
		$array['postcode'] == ''
		)
		{
			$valid = false;
		}
		return $valid;
	}

	public function get_product_by_title($title){
		
		
		$title = str_replace("'","",$title);
		//$title = $this->db->escape($title);
		//$title = addslashes($title);
		
	  $table = 'product';
	  $q= "select * from ".$table." where product like '".$title."' ";
	  $res = $this->db->query($q);
		
    if ( $res->num_rows() > 0 ) 
    {
   		$record = $res->result_array();
   		$record = $record[key($record)];
   	} else {
   		$set =array();
   		$set['product'] = utf8_decode($title);
      $id = $this->Handle_model->insert_record($table,$set);
      $this->db->where('id',$id);
      $res = $this->db->get($table);
      $record = $res->result_array();
      $record = $record[key($record)];
   	}
   return $record;
	}

	public function foreach_insert($set = null) {
		if($set['bedrijf'] == "nalevering"){
			$this->db->select('id');
			$this->db->where(array('desc' => $set['status']));
			$res = $this->db->get('status')->result();	
			if($res != null){
				$set['status_id'] = $res[0]->id;
			}
			else
			{
				 if($set['status'] != ''){
				 	$id = $this->Handle_model->insert_record("status", array('desc' => $set['status']), null);
				 	$set['status_id'] = $id;
				}else{
					$set['status_id'] = '';
				}
			}
			$set['status'] = "active";
		}
		$this->record_id = $this->Handle_model->insert_record($this->dataobject, $set, null);

		$product = array();
		$product['product'] = "";
		foreach($set as $key => $value) {
			switch($key){
				case "envelop":
				case "product":
				case "partner":
				case "retour":
				$product[$key] = $value;
				break;
			}
		}
	}

	public function filter_speciaal_characters($input) {
		$replace = array('&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
	    '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
	    '&Auml;' => 'A', 'Å' => 'A', 'A' => 'A', 'A' => 'A', 'A' => 'A', 'Æ' => 'Ae',
	    'Ç' => 'C', 'C' => 'C', 'C' => 'C', 'C' => 'C', 'C' => 'C', 'D' => 'D', 'Ð' => 'D',
	    'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'E' => 'E',
	    'E' => 'E', 'E' => 'E', 'E' => 'E', 'E' => 'E', 'G' => 'G', 'G' => 'G',
	    'G' => 'G', 'G' => 'G', 'H' => 'H', 'H' => 'H', 'Ì' => 'I', 'Í' => 'I',
	    'Î' => 'I', 'Ï' => 'I', 'I' => 'I', 'I' => 'I', 'I' => 'I', 'I' => 'I',
	    'I' => 'I', '?' => 'IJ', 'J' => 'J', 'K' => 'K', 'L' => 'K', 'L' => 'K',
	    'L' => 'K', 'L' => 'K', '?' => 'K', 'Ñ' => 'N', 'N' => 'N', 'N' => 'N',
	    'N' => 'N', '?' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
	    'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'O' => 'O', 'O' => 'O', 'O' => 'O',
	    'Œ' => 'OE', 'R' => 'R', 'R' => 'R', 'R' => 'R', 'S' => 'S', 'Š' => 'S',
	    'S' => 'S', 'S' => 'S', '?' => 'S', 'T' => 'T', 'T' => 'T', 'T' => 'T',
	    '?' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'U' => 'U',
	    '&Uuml;' => 'Ue', 'U' => 'U', 'U' => 'U', 'U' => 'U', 'U' => 'U', 'U' => 'U',
	    'W' => 'W', 'Ý' => 'Y', 'Y' => 'Y', 'Ÿ' => 'Y', 'Z' => 'Z', 'Ž' => 'Z',
	    'Z' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
	    'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'a' => 'a', 'a' => 'a', 'a' => 'a',
	    'æ' => 'ae', 'ç' => 'c', 'c' => 'c', 'c' => 'c', 'c' => 'c', 'c' => 'c',
	    'd' => 'd', 'd' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
	    'ë' => 'e', 'e' => 'e', 'e' => 'e', 'e' => 'e', 'e' => 'e', 'e' => 'e',
	    'ƒ' => 'f', 'g' => 'g', 'g' => 'g', 'g' => 'g', 'g' => 'g', 'h' => 'h',
	    'h' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'i' => 'i',
	    'i' => 'i', 'i' => 'i', 'i' => 'i', 'i' => 'i', '?' => 'ij', 'j' => 'j',
	    'k' => 'k', '?' => 'k', 'l' => 'l', 'l' => 'l', 'l' => 'l', 'l' => 'l',
	    '?' => 'l', 'ñ' => 'n', 'n' => 'n', 'n' => 'n', 'n' => 'n', '?' => 'n',
	    '?' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
	    '&ouml;' => 'oe', 'ø' => 'o', 'o' => 'o', 'o' => 'o', 'o' => 'o', 'œ' => 'oe',
	    'r' => 'r', 'r' => 'r', 'r' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
	    'û' => 'u', 'ü' => 'ue', 'u' => 'u', '&uuml;' => 'ue', 'u' => 'u', 'u' => 'u',
	    'u' => 'u', 'u' => 'u', 'u' => 'u', 'w' => 'w', 'ý' => 'y', 'ÿ' => 'y',
	    'y' => 'y', 'ž' => 'z', 'z' => 'z', 'z' => 'z', 'þ' => 't', 'ß' => 'ss',
	    '?' => 'ss', '??' => 'iy', '?' => 'A', '?' => 'B', '?' => 'V', '?' => 'G',
	    '?' => 'D', '?' => 'E', '?' => 'YO', '?' => 'ZH', '?' => 'Z', '?' => 'I',
	    '?' => 'Y', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => 'O',
	    '?' => 'P', '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'U', '?' => 'F',
	    '?' => 'H', '?' => 'C', '?' => 'CH', '?' => 'SH', '?' => 'SCH', '?' => '',
	    '?' => 'Y', '?' => '', '?' => 'E', '?' => 'YU', '?' => 'YA', '?' => 'a',
	    '?' => 'b', '?' => 'v', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'yo',
	    '?' => 'zh', '?' => 'z', '?' => 'i', '?' => 'y', '?' => 'k', '?' => 'l',
	    '?' => 'm', '?' => 'n', '?' => 'o', '?' => 'p', '?' => 'r', '?' => 's',
	    '?' => 't', '?' => 'u', '?' => 'f', '?' => 'h', '?' => 'c', '?' => 'ch',
	    '?' => 'sh', '?' => 'sch', '?' => '', '?' => 'y', '?' => '', '?' => 'e',
	    '?' => 'yu', '?' => 'ya', '¬' => '');

		foreach ($input as $key => $array){
			foreach($array as $k => $value) {
				$value = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $value);
				$input[$key][$k] = str_replace(array_keys($replace), $replace, $value);
			}	
		}
	
	return $input;
	}

}