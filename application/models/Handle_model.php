<?php
class Handle_model extends CI_Model{
	
	function update_record($dataobject,$id,$set,$redir_url = null){
		$old = $this->get_record_by_id($dataobject,$id);
		$record = $set + $old;
		$this->db->where('id',$id);
		$this->db->update($dataobject,$this->clean_set($record));
		return $id;
	}
	
	function sql($sql){
		$res = array();
		$data = $this->db->query($sql);
		foreach( $data->result_array() as $row ){
			$res[] = $row;
		}
		return $res;
	}
	
	
	function insert_record($dataobject,$set,$redir_url = null){
		$defaults = $this->get_default_fields();
		$record = $set + $defaults;
		$this->db->insert($dataobject,$this->clean_set($record)); //straks aanzetten
		$id = $this->db->insert_id();
		return  $id;
	}

	public function to_array($do)
	{
		$res = array();
		foreach( $do->result_array() as $row ){
			$res[] = $row;
		}
		return $res;
	}
		
	public function get_default_fields(){
		$res = array();
		$do = $this->db->get('field_defaults');

		$rows = $this->to_array($do);
		foreach($rows as $row){
		if($row['title'] =='updatetime' or $row['title'] == 'createtime'){
				$row['value'] = date('Y-m-d H:i:s',strtotime("now"));
			}
			$res[$row['title']] = $row['value'];
		}
		
		return $res;
	}	

	
	public function get_record_by_id($dataobject,$id){		
		$res = array();
		$do = $this->db->get_where($dataobject,array('id'=>$id,'status'=>'active'));
		foreach( $do->result_array() as $row ){
			$res = $row;
		}
		return $res;
	}
	
	public function get_result_set($dataobject,$array=false){
		$res = array();
		if($array){
		$do = $this->db->get_where($dataobject,$array);
		}
		else{
			$do = $this->db->get($dataobject);
			
		}
		foreach( $do->result_array() as $row ){
			
			$res[] = $row;
		}
		
		return $res;
	}
	
	
	public function clean_set($set){
		foreach($set as $key =>$row){
			if(is_array($row)){
				unset($set[$key]);
				continue;
			}
			if(strpos($key,'_checkbox') != false){
				unset($set[$key]);
			}
			switch($key){
				case "nonce":
				unset($set[$key]);
				break;
			}
		}
		return $set;
	}
}