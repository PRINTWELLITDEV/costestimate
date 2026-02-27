import Swal from "sweetalert2";
// import moment from "moment";

import * as bootstrap from "bootstrap";
// window.bootstrap = bootstrap;

function loadUserTable() {
    $.get(window.appUrl + "/ce/users/user-list", function (html) {
        if ($.fn.DataTable.isDataTable("#users-table")) {
            $("#users-table").DataTable().clear().destroy();
        }

        $("#userTableBody").html(html);

        const usersTable = $("#users-table").DataTable({
            pageLength: 5,
            fixedHeader: true,
            ordering: {
                indicators: true,
                handler: true,
            },
            columnDefs: [{ orderable: false, targets: [4] }],
            responsive: true,
            language: {
                emptyTable: "No users found",
            },
        });
        $("#userSearch").on("keyup", function () {
            usersTable.search(this.value).draw();
        });
    });
}

// Call on page load
if (window.location.pathname.includes("/users")) {
    loadUserTable();
}

// Set CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

// Handle add user button click
$(document).on("click", "#addUserBtn", function (e) {
    e.preventDefault();
    const addModal = document.getElementById("addUserModal");
    const modal = bootstrap.Modal.getInstance(addModal);
    if (modal) {
        modal.hide();
    }
    new bootstrap.Modal(addModal).show();
});

// Handle profile picture preview for add modal
$("#add_profile_pic_url").on("change", function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $("#adduser-profile-preview").attr("src", e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

// Clear Add User modal fields when closed
$("#addUserModal").on("hidden.bs.modal", function () {
    $(this).find("form")[0].reset();
    $("#adduser-profile-preview").attr(
        "src",
        window.appUrl + "/uploads/user-profile/noprofile.png",
    );
    $("#add_profile_pic_url").val("");
});

// Save add user
$("#addUserModal .btn-primary").on("click", function (e) {
    e.preventDefault();
    const form = $("#addUserModal form")[0];
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
                title: data.message || "User added successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#addUserModal").modal("hide");
            form.reset();
            setTimeout(loadUserTable, 500); // Add a 0.5s delay
        },
        error: function (xhr) {
            let msg = "An error occurred";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).join("<br>");
            }
            Swal.fire({
                // toast: true,
                // position: "top-end",
                icon: "error",
                title: msg,
                text: "Please check the form and try again.",
                // showConfirmButton: false,
                // timer: 5000,
                // timerProgressBar: true,
            });
        },
    });
});

// Handle edit user button click
$(document).on("click", ".edit-user-btn", function (e) {
    e.preventDefault();
    const userid = $(this).data("userid");
    const row = $('tr[data-userid="' + userid + '"]');

    // Populate the edit modal with user data
    $("#edit-userid").val(userid);
    $("#edit_userid_display").val(userid);
    $("#edit_name").val(row.data("name"));
    $("#edit_email").val(row.data("email"));
    $("#edit_site").val(row.data("site"));
    $("#edit_level").val(row.data("level"));
    $("#edit_status").val(row.data("status"));
    $("#edit_gender").val(row.data("gender") || null);
    $("#edit_department").val(row.data("department"));
    $("#edit_section").val(row.data("section"));
    $("#edit_position").val(row.data("position"));
    $("#edit_password").val("");
    $("#edit-user-profile-preview").attr("src", row.data("profile"));

    // Show the edit modal
    const editModal = document.getElementById("editUserModal");
    const modal = bootstrap.Modal.getInstance(editModal);
    if (modal) {
        modal.hide();
    }
    new bootstrap.Modal(editModal).show();
});

// Handle profile picture preview for edit modal
$("#edit_profile_pic_url").on("change", function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $("#edit-user-profile-preview").attr("src", e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

// Handle Update User
$("#editUserModal #btnUpdateUser").on("click", function (e) {
    e.preventDefault();
    const form = $("#editUserModal form")[0];
    const formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST", // Laravel expects POST for _method=PUT
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-HTTP-Method-Override": "PUT",
        },
        success: function (data) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: data.message || "User updated successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#editUserModal").modal("hide");
            form.reset();
            setTimeout(loadUserTable, 500);
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

// Handle delete user button click
$(document).on("click", ".delete-user-btn", function (e) {
    e.preventDefault();
    const userid = $(this).data("userid");
    const name = $(this).data("name");
    Swal.fire({
        title: "Delete User?",
        html: `
            You are about to delete this user:<br><br>
            <strong>${name}</strong><br><br>
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
                url: window.appUrl + "/ce/users/delete/" + userid,
                method: "DELETE",
                success: function (data) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: data.message || "User deleted successfully!",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    setTimeout(loadUserTable, 500);
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

// Edit Profile AJAX
$("#editProfileModal form").on("submit", function (e) {
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            Swal.fire({
                icon: "success",
                title: "Profile updated!",
                text: "Your profile has been updated successfully.",
                timer: 2000,
                showConfirmButton: false,
            });
            $("#editProfileModal").modal("hide");
            setTimeout(() => location.reload(), 1500);
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
                title: "Error",
                html: msg,
            });
        },
    });
});

// Change Password AJAX
$("#changePasswordForm").on("submit", function (e) {
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            Swal.fire({
                icon: "success",
                title: "Password changed!",
                text: data.message || "Your password has been changed.",
                timer: 2000,
                showConfirmButton: false,
            });
            $("#changePasswordModal").modal("hide");
            form.reset();
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
                title: "Error",
                html: msg,
            });
        },
    });
});

// Profile picture preview for edit modal
$("#edit_profile_pic").on("change", function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $("#edit-user-profile-preview").attr("src", e.target.result);
        };
        reader.readAsDataURL(file);
    }
});
