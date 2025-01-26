<?php

class DBMysql {
	protected static $_instance = null;
	protected $user;
	protected $pass;
	protected $dbhost;
	protected $dbname;
	protected $dbh = null;
	protected $query = '';
	protected $result = null;
	protected $error = '';
	
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		$this->dbhost = DM_DB_HOST;
		$this->dbname = DM_DB_NAME;
		$this->user = DM_DB_USER;
		$this->pass = DM_DB_PASS;
	}

    protected function connect() {
        if ($this->dbh) {
            return true;
        }

        $this->dbh = mysqli_connect($this->dbhost, $this->user, $this->pass, $this->dbname);

        if (!$this->dbh) {
            die("Can not connect to db: " . mysqli_connect_error());
        }

        if (!mysqli_set_charset($this->dbh, "utf8")) {
            die("Error loading character set utf8: " . mysqli_error($this->dbh));
        }
    }
	
	public function escape($escape_value) {
		$this->connect();
		if (is_array($escape_value)) {
			foreach($escape_value as $key => $value) {
				$escape_value[$key] = mysqli_real_escape_string($value, $this->dbh);
			}
		}else {
			$escape_value = mysqli_real_escape_string($escape_value, $this->dbh);
		}
		return $escape_value;
	}

    public function getArrays() {
        if (!$this->query()) {
            return false;
        }
        $retval = array();
        while ($row = mysqli_fetch_assoc($this->result)) {
            $retval[] = $row;
        }
        return $retval;
    }
	
	public function getArray() {
		if (!$this->query()) {
			return false;
		}
		$retval = mysqli_fetch_assoc($this->result);
		if ($retval === false) {
			return null;
		}
		return $retval;
	}
	
	public function getDBConnection() {
		return $this->dbh;
	}
	
	public function getError() {
		return $this->error;
	}

	public function getObjects() {
		if (!$this->query()) {
			return false;
		}
		$retval = array();
		while($row = mysqli_fetch_object($this->result) ) {
			$retval[] = $row;
		}
		return $retval;
	}

	public function getObject() {
		if (!$this->query()) {
			return false;
		}
		$retval = mysqli_fetch_object($this->result);
		if ($retval === false) {
			return null;
		}
		return $retval;
	}
	
	public function getResult() {
		if (!$this->query()) {
			return false;
		}
		$row = mysqli_fetch_row($this->result);
		if ($row === false) {
			return null;
		}
		return $row[0];
	}
	
	public function insertid() {
		return mysqli_insert_id($this->dbh);
	}

    public function query() {
        $this->result = mysqli_query($this->dbh, $this->query);

        if (!$this->result) {
            $this->error = mysqli_error($this->dbh) . ' Query: ' . $this->query;
            return false;
        }

        return true;
    }
	
	public function setQuery($query) {
		$this->connect();
		$this->query = $query;
	}
}
