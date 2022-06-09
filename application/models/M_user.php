<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user extends CI_Model
{
	private $_user = 'tbl_user';
	public function login($username, $password)
	{
		$query = $this->db->join('tbl_role r', 'tbl_user.id_role = r.id_role')->get_where($this->_user, ['username' => $username, 'password' => $password, "isdeleted" => 0]);
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
}
