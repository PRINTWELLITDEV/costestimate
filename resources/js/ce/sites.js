import Swal from "sweetalert2";
// import moment from "moment";

import * as bootstrap from "bootstrap";
// window.bootstrap = bootstrap;

function loadSiteTable() {
    $.get(
        window.appUrl + "/ce/sites/site-list",
        function (html) {
            if ($.fn.DataTable.isDataTable("#sites-table")) {
                $("#sites-table").DataTable().clear().destroy();
            }  
            $("#siteTableBody").html(html);
            const sitesTable = $("#sites-table").DataTable({
                pageLength: 5,
                fixedHeader: true,
                ordering: {
                    indicators: true,
                    handler: true,
                },
                columnDefs: [
                    { orderable: false, targets: [3] },
                ],
                responsive: true,
                language: {
                    emptyTable: "No sites found",
                },
            });
            $("#siteSearch").on("keyup", function () {
                sitesTable.search(this.value).draw();
            });
        }
    );
}

// Call on page load
if (window.location.pathname.includes('/sites')) {
    loadSiteTable();
}
// Set CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Handle add site button click
$('#saveSiteBtn').on('click', function(e) {
    e.preventDefault();
    const form = $('#addSiteForm')[0];
    const formData = new FormData(form);

    $.ajax({
        url: window.appUrl + "/ce/sites/store",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: data.message || "Site added successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $('#addSiteModal').modal('hide');
            form.reset();
            setTimeout(loadSiteTable, 500);
        },
        error: function(xhr) {
            let msg = "An error occurred";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).join("<br>");
            }
            Swal.fire({
                icon: "error",
                title: msg,
                text: "Please check the form and try again.",
            });
        }
    });
});

// Clear Add Site modal fields when closed
$('#addSiteModal').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
});

// Handle edit site button click
$(document).on('click', '.edit-site-btn', function(e) {
    e.preventDefault();
    const row = $(this).closest('tr');
    $('#edit_site').val(row.data('site'));
    $('#edit_site_desc').val(row.data('site_desc'));
    $('#edit_address').val(row.data('address'));
    $('#edit_site_link').val(row.data('site_link'));
    $('#edit-logo-preview').attr('src', row.data('logo'));
    $('#edit_logo_pic_url').val('');
    const editModal = new bootstrap.Modal(document.getElementById('editSiteModal'));
    editModal.show();
});

// Handle logo preview for edit modal
$('#edit_logo_pic_url').on('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#edit-logo-preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

// Clear Edit Site modal fields when closed
$('#editSiteModal').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
    $('#edit-logo-preview').attr('src', '');
});

// Handle update site button click
$('#updateSiteBtn').on('click', function(e) {
    e.preventDefault();
    const form = $('#editSiteModal form')[0];
    const formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST", // Laravel accepts POST for _method=PUT
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-HTTP-Method-Override': 'PUT'
        },
        success: function(data) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: data.message || "Site updated successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $('#editSiteModal').modal('hide');
            form.reset();
            setTimeout(loadSiteTable, 500);
        },
        error: function(xhr) {
            let msg = "An error occurred";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).join("<br>");
            }
            Swal.fire({
                icon: "error",
                title: msg,
                text: "Please check the form and try again.",
            });
        }
    });
});

// Handle delete site button click
$(document).on('click', '.delete-site-btn', function(e) {
    e.preventDefault();
    const sitecode = $(this).data('siteid'); 
    const site_desc = $(this).data('site_desc');
    
    Swal.fire({
        title: "Delete Site?",
        html: `
            You are about to delete this site:<br><br>
            <strong>${site_desc}</strong><br><br>
            This action cannot be undone.
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        reverseButtons: true,
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-outline-primary",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: window.appUrl + "/ce/sites/delete/" + sitecode,
                method: "DELETE",
                success: function(data) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: data.message || "Site deleted successfully!",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    setTimeout(loadSiteTable, 500);
                },
                error: function(xhr) {
                    let msg = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: msg,
                    });
                }
            });
        }
    });
});