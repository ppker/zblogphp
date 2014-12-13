<?php
/**
 * 数据库操作接口 - 链式
 *
 * @package Z-BlogPHP
 * @subpackage Interface/DataBase 类库
 */

 // 接口什么的就先不写了
 // 先写基本框架


class DbSqlChain {

	private $_sql = array(
		'method' => '',
		'field' => array(),
		'table' => array(),
		'where' => array(),
		'order' => array(),
		'group' => array(),
		'join' => array(
			'inner' => array(),
			'right' => array(),
			'left' => array(),
			'full' => array()
		),
		'union' => array(),
		'custom' => array(),
		'value' => array()
	);

	private $_option = array();

	function __get($name) {
		if ($name == 'sql') {
			return $this->_buildSQL();
		} else {
			return $this->$name;
		}
	}

	function __call($name, $arguments) {

		// PHP 5.2中私有方法不会触发call
		if (in_array($name, array('Where', 'Order', 'Group', 'Union', 'Custom'))) {
			$this->_sql[strtolower($name)] = $arguments[0];
		} else if ($name == 'Join') {
			$this->_sql['join'][$arguments[0]] = $arguments[1];
		}

		return $this;

	}

	private function _parseField() {
		$field = $this->_sql['field'];
		if (!is_array($field)) return $field;
		if (count($field) == 0) return '';
	}

	private function _parseWhere() {
		$where = $this->_sql['where'];
		if (!is_array($where)) return $where;
		if (count($where) == 0) return '';
	}

	private function _parseOrder() {
		$order = $this->_sql['order'];
		if (!is_array($order)) return $order;
		if (count($order) == 0) return '';
	}

	private function _parseGroup() {
		$group = $this->_sql['group'];
		if (!is_array($group)) return $group;
		if (count($group) == 0) return '';
	}

	private function _parseJoin() {
		$join = $this->_sql['join'];
		if (!is_array($join)) return $join;
		if (count($join) == 0) return '';
	}

	private function _parseUnion() {
		$union = $this->_sql['union'];
		if (!is_array($union)) return $union;
		if (count($union) == 0) return '';
	}

	private function _parseCustom() {
		$custom = $this->_sql['custom'];
		if (!is_array($custom)) return $custom;
		if (count($custom) == 0) return '';
	}

	private function _buildSQL() {
		$field  = $this->_parseField();
		$where  = $this->_parseWhere();
		$order  = $this->_parseOrder();
		$group  = $this->_parseGroup();
		$join   = $this->_parseJoin();
		$union  = $this->_parseUnion();
		$custom = $this->_parseCustom();

		$sql = $this->_sql['method'] . ' ';
		var_dump($this->_sql);
		return "";
	}

	public function Select($field, $table) {
		$this->_sql['method'] = 'SELECT';
		$this->_sql['field'][] = $field;
		$this->_sql['table'][] = $table;
		return $this;
	}

	public function Insert($table, $keyvalue) {
		$this->_sql['method'] = 'INSERT';
		$this->_sql['table'][] = $table;
		$this->_sql['value'] = $keyvalue;
		return $this;
	}

	public function Update($table, $keyvalue) {
		$this->_sql['method'] = 'UPDATE';
		$this->_sql['table'][] = $table;
		$this->_sql['value'] = $keyvalue;
		return $this;
	}

	public function Delete($table) {
		$this->_sql['method'] = 'DELETE';
		$this->_sql['table'][] = $table;
		return $this;
	}

}

$db = new DbSqlChain();
$sql = $db->Select('*', 'zbp_post')->Where(array('=', 'log_CateID', 1))->Order('log_ID AS ASC')->Limit('1')->sql;
var_dump($sql);