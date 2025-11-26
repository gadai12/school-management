$(document).ready(function () {
    let table = $('#a').DataTable({
        paging: true,
        pagingType: "simple_numbers",
        searching: true,
        columnDefs: [
            { orderable: false, targets: [2, 3, 4,5,6] } // Disables ordering for columns 2 and 3
        ],
        info: true,
        pageLength: 10,
        ajax: {
            url: 'student_list-code.php',
            type: 'POST',
            data: function (d) {
                d.stdid = $('select[name="filtterstd"]').val();  // Pass the selected standard to PHP
            },
            dataSrc: '',
        },
        columns: [
            { data: "id" },
            { data: "rollno" },
            { data: "fname" },
            { data: "lname" },
            { data: "standard_name" },
            { data: "dept_name" },
            { data: "created_at" },
            { data: "updated_at" },
            {
                data: null, // This column doesn't directly map to a data field
                render: function (data, type, row) {
                    // 'row' contains the entire data object for the current row
                    return `<button class="btn btn-sm btn-info view-user" data-id="${row.id}">View</button>
                            <button class="btn btn-sm btn-warning edit-user" data-id="${row.id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-user" data-id="${row.id}">Delete</button>
                     `;

                },
                orderable: false,
                searchable: false
            }
        ]
    });
    //add btn js
    $('#addbtn').click(function () {
        // Clear form
        $('#addstudent')[0].reset();
        $('.error-message').text('');
        $('#sresponse').text('')
        $('#eresponse').text('');
        $('#error').addClass('d-none');
        $('input[name="id"]').val("");
        $('#addmodalLabel').text("Add Student");

        // Show modal
$('#addmodal').modal('show');
    });
    //fatch single user
    $('#a').on('click', '.view-user', function () {
        const Id = $(this).data('id');
        const view = "view";
        $.ajax({
            url: 'student_list-code.php',
            method: 'POST',
            data: { id: Id, view: view },
            dataType: "json",
            success: function (response) {
                if (response) {
                    $('#fname').text(response.fname);
                    $('#lname').text(response.lname);
                    $('#rollno').text(response.rollno);
                    $('#viewstandard').text(response.standard_name);
                    $('#std').text(response.dept_name);

                    $('#view').modal('show');
                }
            }

        })
    });
    //delete 
    //handle delete user button event
    $('#a').on('click', '.delete-user', function () {
        const Id = $(this).data('id');
        const del = "del";
        $('#confirmModal').modal('show');
        $('#delete').on('click', function () {
            $.ajax({
                url: 'student_list-code.php',
                method: 'POST',
                data: { id: Id, del: del },
                success: function (response) {
                    if (response.status === "success") {
                        $('#confirmModal').modal('hide');
                        $('#response').text(response.message);
                        $('#successModal').modal('show');
                        setTimeout(() => {
                            $('#successModal').modal('hide');
                            $('#response').text('')
                            location.reload();
                        }, 2000);
                    }
                    else {
                        $('#deleteerror').modal('show');
                        setTimeout(() => {
                            $('#deleteerror').modal('hide');
                            $('#responsel').text('')
                        }, 4000);
                    }
                }
            });
        });
    });
    //validation function
    function validation() {
        let isValid = true;
        let rno = $("#rno").val().trim();
        let fname = $('input[name="fname"]').val().trim();
        let lname = $('input[name="lname"]').val().trim();
        let standard = $('select[name="standard"]').val().trim();
        $('.error-message').text('');
        if (!rno) {
            $("#rnoerror").text('rno is required.');
            isValid = false;
        }
        if (!fname) {
            $("#fnameerror").text('fname is required.');
            isValid = false;

        }
        if (!lname) {
            $("#lnameerror").text('lname is required.');
            isValid = false;

        }
        if (!standard) {
            $("#standarderror").text('Please select standard.');
            isValid = false;

        }
        return isValid;
    }
    //add student form submit handle
    $('#addstudent').on('submit', function (e) {
        e.preventDefault();
        const isValid = validation();
        if (isValid) {
            $('.error-message').text('');
            var addformData = new FormData(this);
            addformData.append('add', 'add');
            $.ajax({
                url: 'student_list-code.php',
                method: 'POST',
                data: addformData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status === "success") {
                        $('.error-message').text('');
                        $('#sresponse').text(response.message)
                        $('#success').removeClass('d-none');
                        setTimeout(() => {
                            $('#success').addClass('d-none');
                            $('#error').addClass('d-none');
                            $('#sresponse').text('');
                            $('#eresponse').text('');
                            location.reload();
                        }, 2000);
                    }
                    if (response.status === "error") {
                        $('#success').addClass('d-none');
                        $('#eresponse').text(response.message);
                        $('#error').removeClass('d-none');
                    }
                }
            })
        }
    });
    //edit btn 
    $('#a').on('click', '.edit-user', function () {
        // Clear form
        $('#addstudent')[0].reset();
        $('.error-message').text('');
        $('#sresponse').text('')
        $('#eresponse').text('');
        $('#error').addClass('d-none');
        const Id = $(this).data('id');
        const view = "view";
        $.ajax({
            url: 'student_list-code.php',
            method: 'POST',
            data: { id: Id, view: view },
            success: function (response) {
                if (response) {
                    $('input[name="id"]').val(response.id);
                    $("#rno").val(response.rollno);
                    $('input[name="fname"]').val(response.fname);
                    $('input[name="lname"]').val(response.lname);
                    // $('select[name="standard"]').val(response.standard_name);
                    $('select[name="standard"]').val(response.standard);
                    $('select[name="department"]').val(response.dept_id);
                    $('#addmodalLabel').text("Edit student details");
                    $('#addmodal').modal('show');
                }
            },
            error: function (xhr, error, thrown) {
                console.log(xhr.responseText);
            }
        })

    });
    $('#filter').on('submit', function (e) {
        e.preventDefault();
        let ee = $('select[name="filtterstd"]').val();
        console.log(ee)

        table.ajax.reload();  // Refresh DataTable with new data (filtered by stdid)
    });
});


