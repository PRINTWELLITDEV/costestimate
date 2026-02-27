import Swal from "sweetalert2";
// import moment from "moment";

import * as bootstrap from "bootstrap";
// window.bootstrap = bootstrap;

function loadPTypesTable() {
    $.get(window.appUrl + "/ce/paper-types/list", function (html) {
        if ($.fn.DataTable.isDataTable("#ptype-table")) {
            $("#ptype-table").DataTable().clear().destroy();
        }
        $("#ptypeTableBody").html(html);
        const ptypeTable = $("#ptype-table").DataTable({
            pageLength: 10,
            fixedHeader: true,
            ordering: {
                indicators: true,
                handler: true,
            },
            columnDefs: [
                { 
                    targets: [-1],
                    orderable: false, 
                }
            ],
            responsive: true,
            language: {
                emptyTable: "No paper type found",
            },
        });
        $("#ptypeSearch").on("keyup", function () {
            ptypeTable.search(this.value).draw();
        });
    });
}

// Call on page load
if (window.location.pathname.includes("/paper-types")) {
    loadPTypesTable();
}

// Set CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

// Handle add site button click
$("#savePTypeBtn").on("click", function (e) {
    e.preventDefault();
    const form = $("#addPTypeForm")[0];
    const formData = new FormData(form);

    $.ajax({
        // url: window.appUrl + "/ce/paper-types/store",
        url: $(form).attr("action"),
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: data.message || "Paper Type added successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#addPTypeModal").modal("hide");
            form.reset();
            setTimeout(loadPTypesTable, 500);
        },
        error: function (xhr) {
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
        },
    });
});

// Clear Add ptype modal fields when closed
$("#addPTypeModal").on("hidden.bs.modal", function () {
    $(this).find("form")[0].reset();
});

// Handle edit ptype button click
$(document).on('click', '.edit-ptype-btn', function(e) {
    e.preventDefault();
    const row = $(this).closest('tr');
    $('#edit_site').val(row.data('site'));
    $('#edit_ptype').val(row.data('ptype'));
    $('#update_ptype').val(row.data('ptype'));
    $('#edit_ptypedesc').val(row.data('ptypedesc'));
    $('#edit_desclabel').val(row.data('desclabel'));
    const editModal = new bootstrap.Modal(document.getElementById('editPTypeModal'));
    editModal.show();
});

// Clear Edit PType modal fields when closed
$('#editPTypeModal').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
});

// Handle update ptype button click
$('#updatePTypeBtn').on('click', function(e) {
    e.preventDefault();
    const form = $('#editPTypeModal form')[0];
    const formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST",
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
                title: data.message || "Paper Type updated successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $('#editPTypeModal').modal('hide');
            form.reset();
            setTimeout(loadPTypesTable, 500);
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

// Handle delete button
$(document).on("click", ".delete-ptype-btn", function (e) {
    e.preventDefault();

    const site = $(this).data("site");
    const ptype = $(this).data("ptype");
    const ptypedesc = $(this).data("ptypedesc");

    Swal.fire({
        title: "Delete Paper Type?",
        html: `
            You are about to delete this paper type:<br><br>
            <strong>${ptype} - ${ptypedesc}</strong><br><br>
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
        // buttonsStyling: false,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: window.appUrl + "/ce/paper-types/delete",
                method: "DELETE",
                data: {
                    Site: site,
                    PType: ptype,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (data) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title:
                            data.message || "Paper type deleted successfully!",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    setTimeout(loadPTypesTable, 500);
                },
                error: function (xhr) {
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
                },
            });
        }
    });
});
