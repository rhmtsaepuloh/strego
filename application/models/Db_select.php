<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db_select extends CI_Model {
	public function select_where($table, $where) {
		$this->db->from($table);
		$this->db->where($where);

		$query = $this->db->get();
		$result = $query->row();

      	return $result;
	}

	public function select_all($table) {
		$query = $this->db->get($table);
		$result = $query->result();

      	return $result;
	}
	
	public function select_all_row($table) {
		$query = $this->db->get($table);
		$result = $query->row();

      	return $result;
	}

	public function select_all_join_order($table, $table_join, $id_join, $order) {
		$this->db->from($table);
		$this->db->join($table_join, $id_join);
		$this->db->order_by($order);

		$query = $this->db->get();
		$result = $query->result();

      	return $result;
	}

	public function select_all_join_where($table, $table_join, $id_join, $where) {
		$this->db->from($table);
		$this->db->join($table_join, $id_join);
		$this->db->where($where);

		$query = $this->db->get();
		$result = $query->result();

      	return $result;
	}

	public function select_limit($table) {
		$query = $this->db->get($table);
		$result = $query->result();

      	return $result;
	}

	public function count_all_where($table, $where) {
		$this->db->where($where);
      	$this->db->from($table);
		
		return $this->db->count_all_results();
	}

	public function count_all($table) {
		return $this->db->count_all($table);
	}

	public function select_all_where($table, $where) {
		$this->db->from($table);
		$this->db->where($where);

		$query = $this->db->get();
		$result = $query->result();

      	return $result;
	}

	public function select_all_where_order($table, $where, $order, $sort) {
		$this->db->from($table);
		$this->db->where($where);
		$this->db->order_by($order, $sort);

		$query = $this->db->get();
		$result = $query->result();

      	return $result;
	}

	public function query_all($sql) {
	    $query = $this->db->query($sql);
	    $result = $query->result();

	    return $result;
	}

	public function query($sql) {
	    $query = $this->db->query($sql);
	    $result = $query->row();

	    return $result;
	}
}