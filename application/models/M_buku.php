<?php
class M_buku extends CI_Model
{
	private $_buku = "tbl_buku";
	public function getBuku($id = null)
	{
		if ($id === null) {
			return $this->db->get_where($this->_buku, ['isdeleted' => 0])->result_array();
		} else {
			return $this->db->get_where($this->_buku, ['isdeleted' => 0, 'id_buku' => $id])->result_array();
		}
	}

	public function deleteBuku($id = null)
	{
		$this->db->delete($this->_buku, ['isdeleted' => 0, 'id_buku' => $id]);
		return $this->db->affected_rows();
	}

	public function insertBuku($data)
	{
		$this->db->insert($this->_buku, $data);
		return $this->db->affected_rows();
	}

	public function updateBuku($data, $id)
	{
		$this->db->update($this->_buku, $data, ['id_buku' => $id]);
		return $this->db->affected_rows();
	}
}
