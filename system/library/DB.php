<?php

/**
 *  RainFramework
 *  -------------
 *	Realized by Federico Ulfo & maintained by the Rain Team
 *	Distributed under MIT license http://www.opensource.org/licenses/mit-license.php
 */


/**
 * Class for DB database management (with PDO)
 */

class DB
{

		static protected $db,								// database configurations
						 $fetch_mode = PDO::FETCH_ASSOC,	// define the type of results
						 $statement,						// the PDO object variable
						 $nquery = 0,
						 $link,
						 $config_dir = CONFIG_DIR,
						 $config_file = "db.php";


	/**
	 * Init the database connection
	 */
	static function init() {
		// load the variables
		require self::$config_dir . self::$config_file;

		// Check for available driver
		if (!in_array($db['default']['driver'],PDO::getAvailableDrivers())) {
			die("Error!: could not find a <a href=\"http://php.net/pdo.drivers.php\" target=\"_blank\">" . $db['default']['driver'] . "</a> driver<br/>");
		}
		// Format connection string
		switch ($db['default']['driver']) {
			case 'sqlite2':
			case 'sqlite':
				self::setup("{$db['default']['driver']}:{$db['default']['path']}", $db['default']['username'], $db['default']['password']);
				break;
			case 'mysql':
			default :
				self::setup("{$db['default']['driver']}:host={$db['default']['hostname']};dbname={$db['default']['database']}", $db['default']['username'], $db['default']['password']);
				break;
		}
	}


	/**
	 * Execute a query
	 *
	 * @param string $query
	 * @param array $field if you use PDO prepared query here you going to write the field
	 */
	static function query($query = null, $field = array()) {
		try {
			self::$statement = self::$link->prepare($query);
			self::$statement->execute($field);
			self::$nquery++;
			return self::$statement;
		} catch (PDOException $e) {
			error_reporting("Error!: " . $e->getMessage() . "<br/>", E_USER_ERROR);
		}
	}


	/**
	 * Get the number of rows involved in the last query
	 *
	 * @param string $query
	 * @param array $field
	 * @return string
	 */
	static function count($query = null, $field = array()) {
		return $query ? self::query($query, $field)->rowCount() : self::$statement->rowCount();
	}


	/**
	 * Get one field
	 *
	 * @param string $query
	 * @param array $field
	 * @return string
	 */
	static function get_field($query = null, $field = array()) {
		return self::query($query, $field)->fetchColumn(0);
	}


	/**
	 * Get one row
	 *
	 * @param string $query
	 * @param array $field
	 * @return array
	 */
	static function get_row($query = null, $field = array()) {
		return self::query($query, $field)->fetch(self::$fetch_mode);
	}


	/**
	 * Get a list of rows. Example:
	 *
	 * db::get_all("SELECT * FROM user")  => array(array('id'=>23,'name'=>'tim'),array('id'=>43,'name'=>'max') ... )
	 * db::get_all("SELECT * FROM user","id")  => array(23=>array('id'=>23,'name'=>'tim'),42=>array('id'=>43,'name'=>'max') ... )
	 * db::get_all("SELECT * FROM user","id","name")  => array(23=>'tim'),42=>'max' ...)
	 *
	 * @param string $query
	 * @param string $key
	 * @param string $value
	 * @param array $field
	 * @return array of array
	 */
	static function get_all($query = null, $field = array(), $key = null, $value = null) {
		$rows = array();
		if ($result = self::query($query, $field)->fetchALL(self::$fetch_mode)) {
			if (!$key)
				return $result;
			elseif (!$value)
				foreach ($result as $row)
					$rows[$row[$key]] = $row;
			else
				foreach ($result as $row)
					$rows[$row[$key]] = $row[$value];
		}
		return $rows;
	}


	/**
	 * Get the last inserted id of an insert query
	 * @return
	 */
	static function get_last_id() {
		return self::$link->lastInsertId();
	}


	/**
	 * Set the fetch mode
	 * PDO::FETCH_ASSOC for arrays, PDO::FETCH_OBJ for objects
	 * @param int $fetch_mode
	 */
	static function set_fetch_mode($fetch_mode = PDO::FETCH_ASSOC) {
		self::$fetch_mode = $fetch_mode;
	}


	/**
	 * Insert Into
	 * @param $table
	 * @param $data
	 * @return
	 * @internal param \data $array The parameter must be an associative array (name=>value)
	 */
	static function insert($table, $data) {
		if ($n = count($data)) {
			$fields = implode(',', array_keys($data));
			$values = implode(',', array_fill(0, $n, '?'));
			$prepared = array_values($data);

			return self::query("INSERT INTO $table ($fields) VALUES ($values)", $prepared);
		}
	}


	/**
	 * Update
	 * @param string $table the selected table
	 * @param array $data the parameter must be an associative array (name=>value)
	 * @param $where
	 * @param null $field
	 * @return
	 */
	static function update($table, $data, $where, $field = null) {
		if (!$where) {
			die('You have to set the parameter $where in order to use db::update()');
		}

		if (count($data)) {
			foreach ($data as $field => $value) { // create the fields
				$fields[] = $field . '=?';
			}
			$prepared = array_values($data);
			$fields_query = implode(',', $fields);
			$where = " WHERE $where";

			return self::query("UPDATE $table SET $fields_query $where", $prepared);
		}
	}


	/**
	 * Delete
	 * @param array data The parameter must be an associative array (name=>value)
	 * @param string $where the condition of the row to be deleted
	 */
	static function delete($table, $where) {
		if (!$where) {
			die('You have to set the parameter $where in order to use db::delete()');
		}
		$where = $where;
		return self::query("DELETE FROM $table WHERE $where");
	}


	/**
	 * Begin a transaction
	 * @return
	 */
	static function begin() {
		return self::$link->beginTransaction();
	}


	/**
	 * Commit a transaction
	 * @return
	 */
	static function commit() {
		return self::$link->commit();
	}


	/**
	 * Rollback a transaction
	 * @return mixed
	 */
	static function rollback() {
		return self::$link->rollBack();
	}


	/**
	 * Return the number of executed query
	 * @return int
	 */
	static function get_executed_query() {
		return self::$nquery;
	}


	/**
	 * Return > 0 if connected
	 * @return int
	 */
	static function is_connected() {
		return count(self::$link);
	}


	/**
	 * Connect to the database
	 * @param $string
	 * @param $username
	 * @param $password
	 */
	static function setup($string, $username, $password) {
		try {
			self::$link = new PDO($string, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			self::$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		} catch (PDOException $e) {
			print_r($e);
			die("Error!: " . $e->getMessage() . "<br/>");
		}
	}


	/**
	 * Close mysql connection
	 */
	static function disconnect() {
		unset(self::$link);
	}


	/**
	 * Configure the settings
	 * @param $setting
	 * @param $value
	 */
	static function configure($setting, $value) {
		if (is_array($setting))
			foreach ($setting as $key => $value)
				$this->configure($key, $value);
		else if (property_exists(__CLASS__, $setting))
			self::$$setting = $value;
	}

}

// -- end