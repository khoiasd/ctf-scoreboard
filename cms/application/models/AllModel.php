<?php

/**
 * @author Khoidv1 <khoidv1@viettel.com.vn>
 * @since 20/01/2015
 *
 */
class AllModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	function select_one($table, $where = array(), $select = '*')
	{
		$this->db->select($select);
		foreach ($where as $key => $value) {
			$this->db->where($key, $value);
		}
		$query = $this->db->get($table);
		return $query->row();
	}

	function select_all($args = array())
	{
		/*
		 * ARGS function:
		 * select = String default '*'
		 * table = String
		 * where = array()
		 * order_by = array
		 * limig = int default null
		 *
		 */
		$select = array_key_exists('select', $args) ? $args['select'] : '*';
		$this->db->select($select);
		if (array_key_exists('where', $args)) {
			foreach ($args['where'] as $key => $value) {
				$this->db->where($key, $value);
			}
		}
		if (array_key_exists('join', $args)) {
			foreach ($args['join'] as $key => $value) {
				$this->db->join($key, $value);
			}
		}
		if (array_key_exists('order_by', $args)) {
			foreach ($args['order_by'] as $key => $value) {
				$this->db->order_by($key, $value);
			}
		}

		if (array_key_exists('limit', $args)) {
			$offset = array_key_exists('offset', $args) ? $args['offset'] : 0;
			$this->db->limit($args['limit'], $offset);
		}
		$query = $this->db->get($args['table']);
		return $query->result();
	}

	function insert($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	function update($table, $data, $where=[]){
		foreach ($where as $key => $value) {
			$this->db->where($key, $value);
		}
		$this->db->update($table, $data);
		return ($this->db->affected_rows() > 0);
	}
}
