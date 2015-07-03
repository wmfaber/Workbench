<?php
require_once(getcwd().'/application/libraries/Handle/Handle_Module.php');
Class Order extends Handle_Module {

	public function start_module(){

		$active_modules = get_active_modules();
		$this->settings = $active_modules[$this->module];
		if($this->settings['module']['require_login'] != true || $this->session->userdata('username') != null)
		{
			$this->dataobject = strtolower($this->settings['module']['dataobject']);
			$this->make_list();
		}
		else{
			redirect('login');
		}
	}
	public function ajax(){
		$action = $this->input->post('action');
		$id 		= $this->input->post('id');
		$post_nonce  = $this->input->post('nonce');
		$nonce 	= Handle_Module_Class::create_nonce($this->module,$this->config->config['salt'],$id);
		switch($action){
			case "download_all":
			if($nonce == $post_nonce){
				$data = $this->get_list_download();
				$this->export_to_csv($data);
				$q = 'update `'.$this->dataobject.'` set exported =\'Y\' where  exported =\'N\'';
				$this->db->query($q);
				exit;
			}else{
				redirect('/404');
			}
			break;
		}
		parent::ajax();
	}
	public function make_list() {
		$list = $this->settings['list'];
		$select = array();

		foreach($list as $key => $value)
		{
			if($value == true)
			{
				array_push($select, $key);
			}
		}

		$limit = $this->input->get('loadedentrys') ? $this->input->get('loadedentrys') : '500';

		$this->db->select($select);
		if($limit != 'all'){
			$this->db->limit($limit,0);
		}
		$order_table = $this->db->get($this->dataobject)->result_array();
		$this->db->select(array('product','title','envelop'));
		$product_table = $this->db->get('product')->result_array();

		$new_order_table = array();
		foreach ($order_table as $key => $value)
		{
			foreach($product_table as $k => $v)
			{
				foreach($value as $old_key => $old_val)
				{
					if(!isset($new_order_table[$key][$old_key]))
					{
						$new_order_table[$key][$old_key] = $old_val;
					}
				}
				if($value['product'] == $v['product'])
				{
					$this->db->where('id', $new_order_table[$key]['id']);
					$this->db->update($this->dataobject, array('title' => $v['title'],'envelop' => $v['envelop']));

					if($v['title'] != '')
					{
						$new_order_table[$key]['product'] = $v['title'];
					}
					$new_order_table[$key]['envelop'] = $v['envelop'];
				}
			}
		}
		$this->list_table = $new_order_table;
	}

	public function get_headers_table(){
		if($this->method == ''){ //view
			$head = array(
		'o.id' => 'id',
		'p.envelop' => 'envelop',
		'p.product' => 'product',
		'p.title' => 'title',
		'o.bedrijf' => 'bedrijf',
		'o.naam' => 'naam',
		'o.adres' => 'adres',
		'o.postcode' => 'postcode',
		'o.plaats' => 'plaats',
		'o.aantal' => 'aantal',
		'o.exported' => 'exported',
		'o.vendor_order_id' => 'vendor_order_id',
		'',
		''
		);
		}else{
		$head = array(
		'o.id' => 'id',
		'p.envelop' => 'envelop',
		'p.product' => 'product',
		'p.title' => 'title',
		'o.bedrijf' => 'bedrijf',
		'o.naam' => 'naam',
		'o.adres' => 'adres',
		'o.postcode' => 'postcode',
		'o.plaats' => 'plaats',
		'o.land'=> 'land',
		'o.retour' => 'retour',
		'o.partner' => 'partner',
		'o.aantal' => 'aantal',
		'o.exported' => 'exported',
		'o.status_id' => 'status_id',
		'',
		''
		);
		}
		return $head ;
	}

	public function get_list_download(){
		$data = $this->get_list(array('exported'=>'N'));
		foreach($data as $key => $row){
			unset($data[$key]['id']);
			unset($data[$key]['exported']);
			if($row['title'] != ''){
				$data[$key]['product'] = $row['title'];
			}
			unset($data[$key]['title']);
		}
		return $data;
	}

	public function get_list($param=null){
		$filter = $this->get_list_filter();
		$limit = $this->input->get('loadedentrys') ? $this->input->get('loadedentrys') : '500';
		if($limit != 'all' and $this->input->post('action') != 'download_all'){
			$this->db->limit($limit,0);
		}
		if( $this->input->post('action') != 'download_all'){
			if($filter != false){
				foreach($filter as $key =>$value)
					{	
						if($value and $value != 'all')
						{
							$this->db->like($key,$value);
						}
					}
				}
				else{
				$this->db->where('exported','N');
			}
			}
		$select = array_keys(array_filter($this->get_headers_table()));
		$this->db->select($select)
		->from('order o')
		->join('product p', 'p.id = o.product_id');
		
		if($param){
			foreach($param as $key =>$par){
			$this->db->where($key,$par);
			}
		}
		$this->db->order_by('o.id ','DESC');
		$result = $this->db->get();
		foreach($result->result_array() as $key =>$row){
			unset($row['land']);
			unset($row['partner']);
			$data[$key] = $row;
		}
		return $data;
	}
	

	public function export_to_csv($data, $filename = "export.csv", $delimiter=";") {
		foreach ($data as $row => $index){
			foreach($index as $key => $value){
				if($key == "aantal"){
					if($value){
						$data[$row]['product'] = $value . " x " .$data[$row]['product'];	
					}else{
						$data[$row]['product'] = $data[$row]['product'];	
					}
				}
				if($key == 'status_id' && $value != 0){;
					$this->db->select('desc');
					$this->db->where(array('id' => $value));
					$res = $this->db->get("status")->result();
					$data[$row]['status'] = $res[0]->desc;
				}
				else
				{
					$data[$row]['status'] = "";
				}
			}	
			unset($data[$row]['status_id']);
			unset($data[$row]['aantal']);
		}
		
		$filename = "export-".date("d-m-Y").".csv";
		ob_end_clean();
		$filePath = 'php://output';
		header('Content-Encoding: UTF-8');
		header('Content-Type: application/csv; charset=UTF-8');
		header('Content-Disposition: attachement; filename="'.$filename.'";');
		$f = fopen($filePath, 'w');
		echo "\xEF\xBB\xBF"; // UTF-8 BOM

		$header = array();
		// GET CURRENT HEADERS
		foreach ($data[0] as $key => $value) {
			array_push($header,$key);
		}
		fputcsv($f, $header, $delimiter);
		//unset($data['headers']);
		foreach ($data as $line) {
			fputcsv($f, $line, $delimiter);
		}
		fclose($f);
	}

	public function get_drop_down($name, $default=null){
		$this->db->select(array('id', 'desc'));
		$res = $this->db->get('status')->result_array();
		
		$dropdown = "<select name='".$name."' class='form-control input-xlarge'>";
		$dropdown .= "<option value='0' ></option>";
		foreach($res as $value)
		{
			if($default != null && $default == $value['id'])
				$dropdown .= "<option value='".$value['id']."' selected>".$value['desc']."</option>";
			else
				$dropdown .= "<option value='".$value['id']."' >".$value['desc']."</option>";
		}
		$dropdown .= "</select>";
		return $dropdown;
	}
}