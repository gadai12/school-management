<?php
class Crud
{
    private $con;
    public function __construct($dbConnection)
    {
        $this->con = $dbConnection;
    }
    //  CREATE function
    public function create($table, $columns,$values)
    {
        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        $result=$this->con->query($query);
        return $result;
    }
    //  READ ALL function
    public function readAll($table, $columns = "*", $join = "", $where = "")
    {
        $query = "SELECT $columns FROM $table";
        if (!empty($join)) {
            $query .= " $join";
        }
        if (!empty($where)) {
            $query .= " WHERE $where";
        }
        $result = $this->con->query($query);
        
        return $result;
    }
    //  READ SINGLE function
    public function readSingle($table, $columns = "*", $join = "", $where = "")
    {
        $query = "SELECT $columns FROM $table";
        if (!empty($join)) {
            $query .= " $join";
        }
        if (!empty($where)) {
            $query .= " WHERE $where";
        }
        $result = $this->con->query($query);
        return $result;
    }
    //  soft DELETE and update function 
    public function update($table,$sets = "", $where = "")
    {
        $query = "UPDATE $table ";
        if (!empty($sets) ){
            $query .= " SET  $sets";
        }
       if (!empty($where)) {
            $query .= " WHERE $where";
        }
        return $this->con->query($query);
    }
}
?>