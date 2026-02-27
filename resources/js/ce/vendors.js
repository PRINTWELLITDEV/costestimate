import Swal from "sweetalert2";
// import moment from "moment";

import * as bootstrap from "bootstrap";
// window.bootstrap = bootstrap;

function loadVendorsTable() {
    $.get(window.appUrl + "/ce/vendors/list", function (html) {
        if ($.fn.DataTable.isDataTable("#vendor-table")) {
            $("#vendor-table").DataTable().clear().destroy();
        }
        $("#vendorTableBody").html(html);
        const vendorTable = $("#vendor-table").DataTable({
            pageLength: 10,
            fixedHeader: true,
            columnControl: [['searchList']],
            ordering: {
                indicators: true,
                handler: true,
            },
            columnDefs: [
                { 
                    targets: [-1],
                    orderable: false, 
                    columnControl: [],
                }
            ],
            responsive: true,
            language: {
                emptyTable: "No vendors found",
            },
        });
        $("#vendorSearch").on("keyup", function () {
            vendorTable.search(this.value).draw();
        });
    });
}

// Call on page load
if (window.location.pathname.includes("/vendors")) {
    loadVendorsTable();
}

// Set CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

// Handle add vendor button click
$("#saveVendorBtn").on("click", function (e) {
    e.preventDefault();
    const form = $("#addVendorForm")[0];
    const formData = new FormData(form);

    $.ajax({
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
                title: data.message || "Vendor added successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#addVendorModal").modal("hide");
            form.reset();
            setTimeout(loadVendorsTable, 500);
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

// Clear Add vendor modal fields when closed
$("#addVendorModal").on("hidden.bs.modal", function () {
    $(this).find("form")[0].reset();
});

// Handle edit vendor button click
$(document).on('click', '.edit-vendor-btn', function(e) {
    e.preventDefault();
    const row = $(this).closest('tr');
    $('#edit_vendnum_display').text(row.data('vendnum'));
    $('#edit_site').val(row.data('site'));
    $('#edit_group').val(row.data('group'));
    $('#edit_vendnum').val(row.data('vendnum'));
    $('#edit_name').val(row.data('name'));
    $('#edit_currcode').val(row.data('currcode'));
    const editModal = new bootstrap.Modal(document.getElementById('editVendorModal'));
    editModal.show();
});

// Clear Edit Vendor modal fields when closed
$('#editVendorModal').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
});

// Handle update vendor button click
$('#updateVendorBtn').on('click', function(e) {
    e.preventDefault();
    const form = $('#editVendorModal form')[0];
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
                title: data.message || "Vendor updated successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $('#editVendorModal').modal('hide');
            form.reset();
            setTimeout(loadVendorsTable, 500);
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
$(document).on("click", ".delete-vendor-btn", function (e) {
    e.preventDefault();

    const site = $(this).data("site");
    const vendnum = $(this).data("vendnum");
    const vendname = $(this).data("name");

    Swal.fire({
        title: "Delete Vendor?",
        html: `
            You are about to delete this vendor:<br><br>
            <strong>${vendnum} - ${vendname}</strong><br><br>
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
                url: window.appUrl + "/ce/vendors/delete",
                method: "DELETE",
                data: {
                    Site: site,
                    Vendnum: vendnum,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (data) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title:
                            data.message || "Vendor deleted successfully!",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    setTimeout(loadVendorsTable, 500);
                },
                error: function (xhr) {
                    let msg = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: msg,
                    });
                },
            });
        }
    });
});