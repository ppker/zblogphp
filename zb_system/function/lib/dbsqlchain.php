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
		'value' => array()
	);

	function __get($name) {
		if ($name == 'sql') {
			return $this->_buildSQL();
		} else {
			return $this->$name;
		}
	}

	function __call($name, $arguments) {

		// PHP 5.2中私有方法不会触发call
		if (in_array($name, array('Where', 'Order', 'Group', 'Union'))) {
			$this->_sql[strtolower($name)][] = $arguments[0];
		} else if ($name == 'Join') {
			$this->_sql['join'][$arguments[0]] = $arguments[1];
		}

		return $this;

	}

	private function _buildSQL() {
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