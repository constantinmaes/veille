<?php
class User_model extends CI_model {
	public function __construct()
        {
                $this->load->database();
        }
	public function login($login,$password){//Vérifier si loggé
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('login', $login);
		$results = $this->db->get();
		foreach($results->result() as $row){
			$user = array(
				'login' => $row->login,
				'password' => $row->password,
			);
		}
		if($user['password']==$password){
			$this->session->loggedin = true;
		}
		else{
			$this->session->loggedin = false;
		}
	}
}
?>