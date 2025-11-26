<?php include "layout/header.php";
include "connection.php";
include "crud.php";
?>
<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary d-flex justify-content-between">
            <h1 class="fs-4 pe-4 text-white mb-0"> Result's List </h1>
            <button class="btn btn-outline-light" id="addrsbtn">
                Add Results
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="rs" class="table table-bordered  table-striped text-nowrap">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Stud.id</td>
                            <td>dept.id</td>
                            <td>stand.id</td>
                            <td>cgpa</td>
                            <td>created at</td>
                            <td>updated_at</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- single student code start -->

<!-- Modal -->
<div class="modal fade" id="rview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="viewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewLabel">Result Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Student name: <span id="fname"></span></p>
                <p>department Name: <span id="dept"></span></p>
                <p>standard : <span id="std"></span></p>
                <p>cgpa: <span id="cgpa"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<!-- single student code end -->
<!-- confirmModal  for delete -->
<div class=" modal fade" id="confirmModal" aria-hidden="true" aria-labelledby="confirmModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="rounded-circle  mt-0 m-auto"
                    style="width: 80px; height: 80px; border: 3px solid #f15e5e; text-align: center;">
                    <i class="fa-solid fa-xmark" style="font-size: 46px; color: #f15e5e; margin-top: 13px;"></i>
                </div>
                <div class="d-flex flex-column text-center">
                    <div class="d-flex flex-column  justify-content-center mt-4">
                        <p>Do you really want to delete these records?</p>
                        <div class="mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="delete"
                                style="background-color:#f15e5e">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- delete error  -->
<div class=" modal fade" id="deleteerror" aria-hidden="true" aria-labelledby="deleteerrorLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="rounded-circle  mt-0 m-auto"
                    style="width: 80px; height: 80px; border: 3px solid #f15e5e; text-align: center;">
                    <i class="fa-solid fa-xmark" style="font-size: 46px; color: #f15e5e; margin-top: 13px;"></i>
                </div>
                <div class="d-flex flex-column text-center">
                    <div class="d-flex flex-column  justify-content-center mt-4">
                        <p id="responsel"></p>
                        <div class="mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- delete successfull -->
<div class=" modal fade" id="successModal" aria-hidden="true" aria-labelledby="successModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-body p-4 ">
                <div class="rounded-circle  mt-0 m-auto"
                    style="width: 80px; height: 80px; border: 3px solid #80f15e; text-align: center;">
                    <i class="fa-solid fa-circle-check" style="font-size: 60px; color: #7ef15e; margin-top: 7px;"></i>
                </div>
                <div class="d-flex flex-column text-center">
                    <div class="d-flex flex-column  justify-content-center mt-4">
                        <p id="response"></p>
                        <div class="mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add user modal start -->
<div class="modal fade" id="addresultmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="addresultmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <?php
            $crud = new Crud($conn);
            $students = $crud->readAll("students", "id, fname", "", "status = 1");
          ?>
            <form id="addresult">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addresultmodalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="alert alert-success d-none" role="alert" id="success">
                            <p id="sresponse"></p>
                        </div>
                        <div class="alert alert-danger d-none" role="alert" id="error">
                            <p id="eresponse"></p>
                        </div>
                        <div class="row g-3">
                            <input type="hidden" class="form-control" id="id" name="id">

                            <div class="col-6">
                                <label for="fname" class="form-label">Student Name</label>
                            </div>

                            <div class="col-6 text-start">
                               <select name="student" class="form-control" id="student">
                                    <option value="">-- Select student --</option>
                                    <?php
                                    if ($students && $students->num_rows > 0) {
                                        while ($row = $students->fetch_assoc()) { ?>
                                            <option value="<?php echo $row['id']; ?>">
                                                <?php echo $row['fname']; ?>
                                            </option>
                                        <?php }
                                    } else { ?>
                                        <option value="">No student found</option>
                                    <?php } ?>
                                </select>
                                <span class="error-message text-danger" id="fnameerror"></span>
                            </div>
                             <input type="hidden" class="form-control" id="std_id" name="std_id">
                             <input type="hidden" class="form-control" id="dept_id" name="dept_id">
                            
                            <div class="col-6">
                                <label for="cgpa" class="form-label">CGPA</label>
                            </div>
                            <div class="col-6 text-start">
                                <input  type="number" step="any"  class="form-control" id="cgpa" placeholder="Enter cgpa"
                                    name="cgpa">
                                <span class="error-message text-danger" id="cgpaerror"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <div class="col-6 offset-6 text-center d-grid gap-2">
                        <button type="submit" class="btn btn-primary mx-3">submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add user modal end -->

    <?php include "layout/footer.php" ?>