$(document).ready(function () {
    let table = $('#rs').DataTable({
        paging: true,
        pagingType: "simple_numbers",
        searching: true,
        columnDefs: [
            { orderable: false, targets: [2, 3, 4, 5, 6] } // Disables ordering for columns 2 and 3
        ],
        info: true,
        pageLength: 10,
        ajax: {
            url: 'resultListCode.php',
            type: 'POST',
            dataSrc: '',
        },
        columns: [
            { data: "r_id" },
            { data: "fname" },
            { data: "dept_name" },
            { data: "standard_name" },
            { data: "cgpa" },
            { data: "created_at" },
            { data: "updated_at" },
            {
                data: null, // This column doesn't directly map to a data field
                render: function (data, type, row) {
                    // 'row' contains the entire data object for the current row
                    return `<button class="btn btn-sm btn-info view-result" data-id="${row.r_id}">View</button>
                            <button class="btn btn-sm btn-warning edit-user" data-id="${row.r_id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-user" data-id="${row.r_id}">Delete</button>
                     `;

                },
                orderable: false,
                searchable: false
            }
        ]
    });
    //add btn js
    $('#addrsbtn').click(function () {
        // Clear form
        $('#addresult')[0].reset();
        $('.error-message').text('');
        $('#sresponse').text('')
        $('#eresponse').text('');
        $('#error').addClass('d-none');
        $('input[name="id"]').val("");
        $('#addresultmodalLabel').text("Add Result");
        // Show modal
          $('#addresultmodal').modal('show');
    });
    //fatch single user
    $('#rs').on('click', '.view-result', function () {
        const Id = $(this).data('id');
        const view = "view";
        $.ajax({
            url: 'resultListCode.php',
            method: 'POST',
            data: { id: Id, view: view },
            dataType: "json",
            success: function (response) {
                if (response) {
                    $('#fname').text(response.fname);
                    $('#dept').text(response.dept_name);
                    $('#std').text(response.standard_name);
                    $('#cgpa').text(response.cgpa);
                    $('#rview').modal('show');

                }
            }
        })
    });
    //delete 
    //handle delete user button event
    $('#rs').on('click', '.delete-user', function () {
        const Id = $(this).data('id');
        const del = "del";
        $('#confirmModal').modal('show');
        $('#delete').on('click', function () {
            $.ajax({
                url: 'resultListCode.php',
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
        let fname = $('select[name="student"]').val();
        let cgpa = $('input[name="cgpa"]').val();
        $('.error-message').text('');
            if (!fname) {
            $("#fnameerror").text('fname is required.');
            isValid = false;

        }
        if (!cgpa) {
            $("#cgpaerror").text('lname is required.');
            isValid = false;

        }
        
        return isValid;
    }
    //fatch standar and department base on student
    $('#student').on('change', function () {
        let sid = $(this).val();
         const view = "view";
        $.ajax({
            url: 'student_list-code.php',
            method: 'POST',
            data: { id: sid, view: view },
            dataType: "json",
            success: function (response) {
                if (response) {
                    $('#std_id').val(response.standard);
                    $('#dept_id').val(response.dept_id);
                }
            }
        })

    });
    //add result form submit handle
    $('#addresult').on('submit', function (e) {
        e.preventDefault();
        const isValid = validation();
        if (isValid) {
            $('.error-message').text('');
            var addformData = new FormData(this);
            addformData.append('add', 'add');
            $.ajax({
                url: 'resultListCode.php',
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
    $('#rs').on('click', '.edit-user', function () {
        // Clear form
        $('#addresult')[0].reset();
        $('.error-message').text('');
        $('#sresponse').text('')
        $('#eresponse').text('');
        $('#error').addClass('d-none');
        const Id = $(this).data('id');
        const view = "view";
        $.ajax({
            url: 'resultListCode.php',
            method: 'POST',
            data: { id: Id, view: view },
            success: function (response) {
                if (response) {
                    $('input[name="id"]').val(response.r_id);
                    $('select[name="student"]').val(response.s_id);
                    $('input[name="std_id"]').val(response.std_id);
                    $('input[name="dept_id"]').val(response.d_id);
                    $('input[name="cgpa"]').val(response.cgpa);
                    $('#addresultmodalLabel').text("Edit student details");
                    $('#addresultmodal').modal('show');
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


