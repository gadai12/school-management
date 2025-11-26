<?php
class Database
{
  private $servername = "localhost";
  private $username = "root";
  private $password = "root";
  private $database = "school_db";
  public $con;

  public function __construct()
  {
    $this->connect();
  }
  private function connect()
  {
    $this->con = new mysqli(
      $this->servername,
      $this->username,
      $this->password,
      $this->database

    );
    // Check connection
    if ($this->con->connect_error) {
      die("Connection failed: " . $this->con->connect_error);
    }
  }
}
$db = new Database();
$conn = $db->con;

?>

