<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "connection.php";
include "crud.php";
header('Content-Type: application/json');

class resultListCode
{
     private $baseTable = "result AS r";
    private $baseFields = "r.id as r_id,s.id as s_id,s.fname,d.id as d_id,d.dept_name,std.id as std_id,std.standard_name,r.cgpa,r.created_at,r.updated_at";
    private $baseJoin  = "LEFT JOIN students AS s ON r.stud_id=s.id LEFT JOIN department AS d ON r.dept_id=d.id LEFT JOIN standards AS std ON r.stand_id=std.id";
    private $con;
    public function __construct()
    {
        $db = new Database();
        $this->con = $db->con;
    }
    //method to get all student
    public function getAllResults()
    {

        $crud = new Crud($this->con);
        $result = $crud->readAll(
            $this->baseTable,
            $this->baseFields,
            $this->baseJoin,
            "r.status = 1"

        );
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    //method to  get single student
    public function viewSingleResult($id)
    {
        $crud = new Crud($this->con);
        $result = $crud->readAll(
            $this->baseTable,
            $this->baseFields,
            $this->baseJoin,
            "r.id=$id"

        );
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();  // return one row
        } else {
            return ["error" => "User not found"];
        }
    }
    // method for delete student 
    public function deleteResult($id)
    {
        $crud = new Crud($this->con);
        $result = $crud->update(
            "result",
            "status='0'",
            "id=$id"
        );
        if ($result) {
            return [
                'status' => 'success',
                'message' => 'Result Delete successfully!'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Delete Unsuccessfully!'
            ];
        }
    }
    //method for inser & edit student
    public function saveResult($id, $stud_id, $dept_id, $stand_id, $cgpa)
    {
        if (!$id) {
            $cq = "SELECT * FROM result WHERE stud_id=$stud_id";
            $result = $this->con->query($cq);
            $row = $result->num_rows;
            if ($row > 0) {
                return [
                    'status' => 'error',
                    'message' => ' result not added!this student result alredy exist in the table '
                ];
            } else {
                $crud = new Crud($this->con);
                $result = $crud->create(
                    "result",
                    "stud_id,dept_id,stand_id,cgpa",
                    "'$stud_id','$dept_id','$stand_id','$cgpa'"
                );
                if ($result) {
                    return [
                        'status' => 'success',
                        'message' => 'result Added successfully!'
                    ];
                } else {
                    return [
                        'status' => 'error',
                        'message' => 'result not added!'
                    ];
                }

            }
        } else {
            $eq = "SELECT * FROM result WHERE id=$id";
            $result = $this->con->query($eq);
            $olddata = $result->fetch_assoc();
            if ($stud_id === $olddata['stud_id'] AND $dept_id === $olddata['dept_id'] AND $stand_id === $olddata['stand_id']) {
                $crud = new Crud($this->con);
                $result = $crud->update(
                    "result",
                    "cgpa='$cgpa'",
                    "id=$id"
                );
                if ($result) {
                    return [
                        'status' => 'success',
                        'message' => 'student result details edit successfully!'
                    ];
                }
            } else {
                $cq = "SELECT * FROM result WHERE stud_id='$stud_id'";
                $result = $this->con->query($cq);
                $row = $result->num_rows;
                if ($row > 0) {
                    return [
                        'status' => 'error',
                        'message' => ' result not edited! this student result allredy available'
                    ];
                } else {
                    $crud = new Crud($this->con);
                    $result = $crud->update(
                        "result",
                        "stud_id='$stud_id',dept_id='$dept_id',stand_id='$stand_id',cgpa='$cgpa'",
                        "id=$id"
                    );
                    if ($result) {
                        return [
                            'status' => 'success',
                            'message' => 'student result details edit successfully!'
                        ];
                    }
                }
            }
        }
    }
}
;
// Create object allstudent
if (!isset($_POST['id'])) {
    $getallResults = new resultListCode();
    // $stdid =  (int)$_POST['stdid'] ;
    echo json_encode($getallResults->getAllResults());
}
//single user 
if (isset($_POST['id']) && isset($_POST['view'])) {
    $getsingleresult = new resultListCode();
    $id = $_POST['id'];
    echo json_encode($getsingleresult->viewSingleResult($id));
}
//delete user 
if (isset($_POST['id']) && isset($_POST['del'])) {
    $deleteresult = new resultListCode();
    $id = $_POST['id'];
    echo json_encode($deleteresult->deleteResult($id));
}
//add and edi user 
if (isset($_POST['add'])) {
    $id = $_POST['id'];
    $stud_id = $_POST['student'];
    $dept_id = $_POST['dept_id'];
    $stand_id = $_POST['std_id'];
    $cgpa = $_POST['cgpa'];
    $save = new resultListCode();
    echo json_encode($save->saveResult($id, $stud_id, $dept_id, $stand_id, $cgpa));
}
?>