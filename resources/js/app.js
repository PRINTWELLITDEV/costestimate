import "bootstrap";
import "admin-lte";
import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", function () {
    // Login Form
    const loginForm = document.getElementById("LoginForm");
    const nextButton = document.getElementById("nextButton");
    if (loginForm && nextButton) {
        nextButton.addEventListener("click", function (e) {
            handleLoginSubmit(e, loginForm);
        });
    }
});

function handleLoginSubmit(e, form) {
    e.preventDefault();

    const userid = form.querySelector('input[name="userid"]').value.trim();
    const password = form.querySelector('input[name="password"]').value.trim();

    if (!userid || !password) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: 'Enter your User ID and Password',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }

    const formData = new FormData(form);

    fetch(form.action, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
            Accept: "application/json",
        },
        body: formData,
    })
    .then(async (response) => {
        let data = {};
        try {
            data = await response.json();
        } catch {}

        Swal.close();

        if (response.ok && data.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Login Successful!',
                text: 'Redirecting to dashboard...',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            setTimeout(() => {
                window.location.href = data.redirect || (window.appUrl + "/ce");
            }, 500);
        } else {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: data.message || 'invalid credentials.',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            // Clear fields on wrong credentials
            form.querySelector('input[name="userid"]').value = '';
            form.querySelector('input[name="password"]').value = '';
        }
    })
    .catch(() => {
        Swal.close();
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Server Error',
            text: 'Unable to process login. Please try again later.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    });
}