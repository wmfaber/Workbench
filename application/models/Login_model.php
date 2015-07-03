<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login_model extends CI_Model
{
     function __construct()
     {
          // Call the Model constructor
          parent::__construct();
     }

     //get the username & password from tbl_usrs
     function get_user($usr, $pwd)
     {
          $sql = "SELECT * FROM `user_login` WHERE `email`='" . $usr . "' AND `password`='" . md5($pwd) . "' AND `active`='Y'";
          $query = $this->db->query($sql);
          return $query->result();
     }
}?>