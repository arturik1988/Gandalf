<?php
class Auth {

	/**
	 * The session name used when signing in.
	 *
	 * @var string
	 */
	protected $_sessionName = "auth";

	/**
	 * The PDO object, responsible for all database operations.
	 *
	 * @var object
	 */
	protected $_pdo;

	/**
	 * Whether the user is signed in or not.
	 *
	 * @var bool
	 */
	protected $_signedIn = false;

	/**
	 * A collection of publicly safe errors.
	 *
	 * @var array
	 */
	public $errors = [];

	/**
	 * If debug mode is on or off. This should always be off for production.
	 *
	 * @var bool
	 */
	public $debug = false;

	/**
	 * Constructor for jobs when Auth is instantiated.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->debug = Config::get("debug");

		$this->connect();

		if($this->check()) {
			$this->_signedIn = true;
		}
	}

	/**
	 * Connect to the database.
	 *
	 * @return void
	 */
	protected function connect() {
		// Get configuration options required
		$driver 	= Config::get("database.driver");
		$host 		= Config::get("database.host");
		$db 		= Config::get("database.db");
		$username 	= Config::get("database.username");
		$password 	= Config::get("database.password");

		try {
			switch($driver) {
				case "mysql":
					$this->_pdo = new PDO("mysql:host={$host};dbname={$db}", $username, $password);
				break;
				default:
					throw new Exception("Driver {$driver} not supported.");
				break;
			}

			// Turn on error output from PDO if debugging is on
			if($this->debug) {
				$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}

		} catch(PDOException $e) {
			die(($this->debug) ? $e->getMessage() : "");
		}
	}

	/**
	 * Add an error to the error array.
	 *
	 * @param string $error
	 * @return void
	 */
	protected function addError($error) {
		$this->errors[] = $error;
	}

	/**
	 * Get all errors added to the error array.
	 *
	 * @return array
	 */
	public function errors() {
		return $this->errors;
	}

	/**
	 * Check if there are errors in the error array.
	 *
	 * @return bool
	 */
	public function hasErrors() {
		return count($this->errors()) ? true : false;
	}

	/**
	 * Build the users table.
	 *
	 * @return bool
	 */
	public function build() {
		$sql = "CREATE TABLE IF NOT EXISTS users
				(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				email VARCHAR(200),
				username VARCHAR(20),
				password VARCHAR(255))";

		return $this->_pdo->query($sql) ? true : false;
	}

	/**
	 * Check if user session exists.
	 *
	 * @return bool
	 */
	public function check() {
		return (isset($_SESSION[$this->_sessionName])) ? true : false;
	}

	/**
	 * Check if a user exists in the users table.
	 *
	 * @param string $username
	 * @return bool
	 */
	public function userExists($username) {
		$sql = "SELECT * FROM users
				WHERE username = {$this->_pdo->quote($username)}";

		return $this->_pdo->query($sql)->rowCount() === 1 ? true : false;
	}

	/**
	 * Create a user.
	 *
	 * @param array $data
	 * @return bool
	 */
	public function create($data = array()) {
		if(count($data)) {

			if($this->userExists($data["username"])) {
				$this->addError("That user already exists");
			} else {
				$sql = "INSERT INTO users (`email`, `username`, `password`)
						VALUES (:email, :username, :password)";

				$stmt = $this->_pdo->prepare($sql);

				$create = $stmt->execute(array(
					"email" => $data["email"],
					"username" => $data["username"],
					"password" => Hash::make($data["password"])
				));

				if(!$create) {
					$this->addError("Problem creating account.");
				}

				return $create;
			}
		}

		return false;
	}

	/**
	 * Sign a user in.
	 *
	 * @param array $data
	 * @return bool
	 */
	public function signin($data = array()) {
		if(count($data)) {
			$username = $data["username"];
			$password = $data["password"];

			$sql = "SELECT id, password
					FROM users
					WHERE username = ?";

			$stmt = $this->_pdo->prepare($sql);

			$stmt->execute(array($username));

			if((int)$stmt->rowCount() === 1) {
				$user = $stmt->fetchAll(PDO::FETCH_OBJ)[0];

				if(Hash::verify($password, $user->password)) {
					$_SESSION[$this->_sessionName] = $user->id;
					return true;
				} else {
					$this->addError("Could not authenticate user {$username}");
				}
			} else {
				$this->addError("Could not find user {$username}");
			}
		}

		return false;
	}

	/**
	 * Sign a user out.
	 *
	 * @return void
	 */
	public function signout() {
		unset($_SESSION[$this->_sessionName]);
	}
}