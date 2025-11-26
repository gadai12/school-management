<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "connection.php";
include "crud.php";
header('Content-Type: application/json');
class studentListCode
{
  private $con;
  public function __construct()
  {
    $db = new Database();
    $this->con = $db->con;

  }
  //method to get all student
  public function getAllStudents($stdid)
  {
    if ($stdid > 0) {
      $crud = new Crud($this->con);
      $result= $crud->readAll(
        "students AS s",
        "s.id, s.rollno, s.fname, s.lname, std.standard_name,d.dept_name , s.created_at, s.updated_at",
        "LEFT JOIN standards AS std ON s.standard = std.id LEFT JOIN  department AS d ON s.dept_id = d.id ",
        "s.status = 1 AND s.standard=$stdid"
      );

    } else {
      $crud = new Crud($this->con);
      $result= $crud->readAll(
        "students AS s",
        "s.id, s.rollno, s.fname, s.lname, std.standard_name,d.dept_name  ,s.created_at, s.updated_at",
        "LEFT JOIN standards AS std ON s.standard = std.id LEFT JOIN  department AS d ON s.dept_id = d.id ",
        "s.status = 1"
      );
    }
    $data  = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
  }
  //method to  get single student
  public function viewSingleStudent($id)
  {
    $crud = new Crud($this->con);
    $result = $crud->readSingle(
      "students As s",
      "s.id, s.rollno, s.fname, s.lname, s.standard, std.standard_name,s.dept_id,d.dept_name ,s.created_at, s.updated_at",
      "LEFT JOIN standards AS std ON s.standard = std.id LEFT JOIN  department AS d ON s.dept_id = d.id ",
      "s.status = 1 AND s.id=$id"
    );
    if ($result->num_rows > 0) {
      return $result->fetch_assoc();  // return one row
    } else {
      return ["error" => "Student not found"];
    }
  }
  // method for delete student 
  public function deleteStudent($id)
  {
    $crud = new Crud($this->con);
    $result = $crud->update(
      "students",
      "status='0'",
      "id=$id"
    );
    if ($result) {
      return [
        'status' => 'success',
        'message' => 'Student  Delete successfully!'
      ];
    } else {
      return [
        'status' => 'error',
        'message' => 'Delete Unsuccessfully!'
      ];
    }
  }
  //method for inser & edit student
  public function saveStudent($id, $rollno, $fname, $lname, $standard, $department)
  {
    if (!$id) {
      $cq = "SELECT * FROM students WHERE rollno='$rollno' AND  standard='$standard'";
      $result = $this->con->query($cq);
      $row = $result->num_rows;
      if ($row > 0) {
        return [
          'status' => 'error',
          'message' => ' user not added! roll no alredy exist in this standard'
        ];
      } else {
        // $aq = "INSERT INTO students (rollno,fname,lname,standard) VALUES ('$rollno','$fname','$lname','$standard')";
        $crud = new Crud($this->con);
        $result = $crud->create(
          "students",
          "rollno,fname,lname,standard,dept_id",
          "'$rollno','$fname','$lname','$standard','$department'"
        );
        if ($result) {
          return [
            'status' => 'success',
            'message' => 'User Added successfully!'
          ];
        } else {
          return [
            'status' => 'error',
            'message' => 'user not added!'
          ];
        }
      }
    } else {
      $eq = "SELECT * FROM students WHERE id=$id";
      $result = $this->con->query($eq);
      $olddata = $result->fetch_assoc();
      if ($rollno === $olddata['rollno'] && $standard === $olddata['standard']) {
        // $uq = "UPDATE students SET fname='$fname',lname='$lname' WHERE id='$id'";
        $crud = new Crud($this->con);
           $result= $crud->update(
            "students",
            "fname='$fname',lname='$lname',dept_id='$department'",
            "id=$id"
           );
        if ($result) {
          return [
            'status' => 'success',
            'message' => 'User details edit successfully!'
          ];
        }
      } else {
        $cq = "SELECT * FROM students WHERE rollno='$rollno' AND  standard='$standard'";
        $result = $this->con->query($cq);
        $row = $result->num_rows;
        if ($row > 0) {
          return [
            'status' => 'error',
            'message' => ' user not edited! roll no alredy exist in this standard'
          ];
        } else {
          // $uq = "UPDATE students SET rollno='$rollno', fname='$fname',lname='$lname', standard='$standard' WHERE id='$id' ";
           $crud = new Crud($this->con);
           $result= $crud->update(
            "students",
            "rollno='$rollno',fname='$fname',lname='$lname',standard='$standard',dept_id='$department'",
            "id=$id"
           );
          if ($result) {
            return [
              'status' => 'success',
              'message' => 'User details edit successfully!'
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
  $getallstudent = new studentListCode();
  $stdid = (int) $_POST['stdid'];
  echo json_encode($getallstudent->getAllStudents($stdid));
}
//single user 
if (isset($_POST['id']) && isset($_POST['view'])) {
  $getsinglestudent = new studentListCode();
  $id = $_POST['id'];
  echo json_encode($getsinglestudent->viewSingleStudent($id));
}
//delete user 
if (isset($_POST['id']) && isset($_POST['del'])) {
  $deletestudentt = new studentListCode();
  $id = $_POST['id'];
  echo json_encode($deletestudentt->deleteStudent($id));
}
//add and edi user 
if (isset($_POST['add'])) {
  $id = $_POST['id'];
  $rollno = $_POST['rno'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $standard = $_POST['standard'];
  $department = $_POST['department'];
  $save = new studentListCode();
  echo json_encode($save->saveStudent($id, $rollno, $fname, $lname, $standard, $department));
}


?>