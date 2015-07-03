<?php
require_once(getcwd().'/application/libraries/Handle/Handle_Module.php');

Class User extends Handle_Module {
	
	public function registreer()
	{
		$this->form->open = form_open($this->module.'/insert');
		foreach($this->settings['login'] as $field => $show){
			if($show){
				$type = get_database_to_formfield($field);
				$this->form->element[$field]['label'] = form_label($field);
				if($this->record_id){
					$this->form->element[$field]['input'] =  form_input(array('id'=>$field,'name'=>$field,'value'=>$this->record[$field],'type'=>$type));
				}else{
					$this->form->element[$field]['input'] =	 form_input(array('id'=>$field,'name'=>$field,'type'=>$type));
				}
				$this->form->element[$field]['type'] =	 $type;
			}
		}
	  $this->form->submit = form_submit(array('id'=>'submit','value'=>'Opslaan','class'=>'btn'));
	  $this->form->close = form_close();
	  
	  $this->data['form'] = $this->form;
	  $this->data['action'] = 'form';
	  $this->create_data();
	}
}