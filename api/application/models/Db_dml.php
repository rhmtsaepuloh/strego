<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class db_dml extends CI_Model {
	public function update($table, $data, $where) {
		$this->db->where($where);
		$this->db->update($table, $data);

		return $this->db->affected_rows();
	}

	public function delete($table, $where) {
		$this->db->where($where);
		$this->db->delete($table);

		return $this->db->affected_rows();
	}

	public function insert($table, $data) {
		$this->db->insert($table, $data);

		$insert_id = $this->db->insert_id();

   		return $insert_id;
	}

	public function normal_insert($table, $data) {
		$this->db->insert($table, $data);

		return $this->db->affected_rows();
	}

	public function insert_batch($table, $data) {
		$this->db->insert_batch($table, $data); 

		return $this->db->affected_rows();
	}
}