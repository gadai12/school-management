<?php include "layout/header.php";
include "connection.php";
?>
<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary d-flex justify-content-between">
            <h1 class="fs-4 pe-4 text-white mb-0"> Student's List </h1>
            <button class="btn btn-outline-light" id="addbtn">
                Add Students
            </button>
        </div>
        <div class="card-body">
            <!-- for filleter -->
            <?php
            $q = "SELECT id,standard_name FROM standards";
            $result = $conn->query($q);
            ?>
            <form id="filter">
                <div class="row offset-9 d-flex justify-content-between">

                    <div class="col-10">
                        <select name="filtterstd" class="form-select " id="">
                            <option value="">filter Standard</option>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo $row['standard_name']; ?>
                                    </option>
                                <?php }
                            } else { ?>
                                <option value="">No standards found</option>
                             <?php } ?>
                        </select>

                    </div>
                    <button  id="serch" class="btn btn-primary col-2" type="submit"><i
                            class="fa-brands fa-searchengin"></i></button>
                </div>
            </form>
            <div class="table-responsive">
                <table id="a" class="table table-bordered  table-striped text-nowrap">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Roll No</td>
                            <td>Fname</td>
                            <td>Lname</td>
                            <td>Standard</td>
                            <td>Department</td>
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
<div class="modal fade" id="view" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="viewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewLabel">Student Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>First Name: <span id="fname"></span></p>
                <p>Last Name: <span id="lname"></span></p>
                <p>Roll No: <span id="rollno"></span></p>
                <p>Standard: <span id="viewstandard"></span></p>
                <p>Department: <span id="std"></span></p>

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
<div class="modal fade" id="addmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="addmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form id="addstudent">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addmodalLabel"></h1>
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
                                <label for="rno" class="form-label">Roll No</label>
                            </div>

                            <div class="col-6 text-start">
                                <input type="num" class="form-control" id="rno" placeholder="Enter Roll No" name="rno">
                                <span class="error-message text-danger" id="rnoerror"></span>
                            </div>
                            <div class="col-6">
                                <label for="fname" class="form-label">First Name</label>
                            </div>
                            <div class="col-6 text-start">
                                <input type="text" class="form-control" id="fname" placeholder="Enter Student Fname"
                                    name="fname">
                                <span class="error-message text-danger" id="fnameerror"></span>
                            </div>
                            <div class="col-6">
                                <label for="lname" class="form-label">Last Name</label>
                            </div>
                            <div class="col-6 text-start">
                                <input type="text" class="form-control" id="lname" placeholder="Enter Student Lname"
                                    name="lname">
                                <span class="error-message text-danger" id="lnameerror"></span>
                            </div>
                            <?php
                            $q = "SELECT id,standard_name FROM standards";
                            $result = $conn->query($q);
                            ?>
                            <div class="col-6">
                                <label for="standard" class="form-label">Standard</label>
                            </div>
                            <div class="col-6 text-start">
                                <select name="standard" class="form-control" id="standard">
                                    <option value="">-- Select Standard --</option>
                                    <?php
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <option value="<?= htmlspecialchars($row['id']); ?>">
                                                <?= htmlspecialchars($row['standard_name']); ?>
                                            </option>
                                        <?php }
                                    } else { ?>
                                        <option value="">No standards found</option>
                                    <?php } ?>
                                </select>
                                <span class="error-message text-danger" id="standarderror"></span>
                            </div>
                             <?php
                            $q = "SELECT id,dept_name FROM department";
                            $result = $conn->query($q);
                            ?>
                            <div class="col-6">
                                <label for="department" class="form-label">department</label>
                            </div>
                            <div class="col-6 text-start">
                                <select name="department" class="form-control" id="department">
                                    <option value="">-- Select department --</option>
                                    <?php
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <option value="<?= htmlspecialchars($row['id']); ?>">
                                                <?= htmlspecialchars($row['dept_name']); ?>
                                            </option>
                                        <?php }
                                    } else { ?>
                                        <option value="">No department found</option>
                                    <?php } ?>
                                </select>
                                <span class="error-message text-danger" id="standarderror"></span>
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