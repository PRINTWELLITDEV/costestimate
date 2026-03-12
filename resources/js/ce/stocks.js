import Swal from "sweetalert2";
// import moment from "moment";

import * as bootstrap from "bootstrap";
// import { normalizeUnits } from "moment";
// window.bootstrap = bootstrap;

// Store all ptypes data in memory
let allPtypes = [];

// Load ptypes data via AJAX
function loadPtypesData() {
    return $.get(window.appUrl + '/ce/stocks/api/ptypes', function(data) {
        allPtypes = data;
    });
}

// Filter ptypes by site
function filterPTypesBySite(siteCode) {
    if (!siteCode) return allPtypes;
    return allPtypes.filter(p => p.Site === siteCode);
}

// Populate ptype select with filtered data
function populatePtypeSelect(data) {
    const $select = $('#ptype');
    const currentValue = $select.val();
    
    $select.html('<option disabled selected>Select Type</option>');
    
    data.forEach(stock => {
        $select.append(
            $('<option></option>')
                .attr('value', stock.PType)
                .attr('data-desclabel', stock.DescLabel)
                .text(stock.PType + ' - ' + stock.PTypeDesc)
        );
    });
    
    // Re-initialize Select2
    $select.select2({
        placeholder: 'Select Type',
        width: '100%'
    });
}

function loadStocksTable() {
    $.get(window.appUrl + "/ce/stocks/list", function (html) {
        if ($.fn.DataTable.isDataTable("#stock-table")) {
            $("#stock-table").DataTable().clear().destroy();
        }
        $("#stockTableBody").html(html);
        const stockTable = $("#stock-table").DataTable({
            pageLength: 10,
            fixedHeader: true,
            // columnControl: [["searchList"]],
            ordering: {
                indicators: true,
                handler: true,
            },
            responsive: true,
            language: {
                emptyTable: "No stocks found",
            },
        });
        $("#stockSearch").on("keyup", function () {
            stockTable.search(this.value).draw();
        });
    });
}


// Call on page load
if (window.location.pathname.includes("/stocks")) {
    loadStocksTable();
}

// Set CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function disableFormFields(fields, disable) {
    fields.forEach((field) => {
        field.disabled = disable;
        if (disable) {
            field.classList.add("bg-gray", "bg-opacity-25");
        } else {
            field.classList.remove("bg-gray", "bg-opacity-25");
        }
    });
}

// Initialize form functionality
function initializeStockForm() {
    // Load ptypes data first
    loadPtypesData().then(() => {
        // Initialize Select2 for ptype
        $('#ptype').select2({
            placeholder: 'Select Type',
            // allowClear: true,
            width: '100%'
        });
        
        // Add Select2 specific event listener for ptype
        $('#ptype').on('select2:select', function (e) {
            generateStockCode();
        });

        const form = document.getElementById("addStockForm");
        if (!form) return;

        const siteSelect = document.getElementById("site");
        const formFields = form.querySelectorAll(
            'input:not([type="hidden"]), select:not(#site)',
        );

        // Check if user level is 1 (admin) or not
        const isAdmin = siteSelect !== null;

        if (isAdmin) {
            // Admin user - disable all fields initially
            disableFormFields(formFields, true);

            // Enable fields when site is selected and filter ptypes
            siteSelect.addEventListener("change", function () {
                if (this.value) {
                    disableFormFields(formFields, false);
                    // Filter and populate ptypes based on selected site
                    const filteredPtypes = filterPTypesBySite(this.value);
                    populatePtypeSelect(filteredPtypes);
                } else {
                    disableFormFields(formFields, true);
                    clearForm();
                    // Reset ptype select
                    populatePtypeSelect(allPtypes);
                }
            });
        } else {
            // Non-admin user - fields are already enabled
            disableFormFields(formFields, false);
            // Populate ptypes with user's site
            const userSite = siteSelect ? siteSelect.value : null;
            const filteredPtypes = filterPTypesBySite(userSite);
            populatePtypeSelect(filteredPtypes);
        }

        // Add event listeners for auto-generation
        AutoGenerationListeners();

        // Initialize leading zero removal
        initializeLeadingZeroRemoval();
    });
}

function initializeEditStockForm() {
    // Product Group select2 and set value
    $('#product_group').select2({
        placeholder: 'Select Product Group',
        width: '100%'
    });
    const currentGroup = $('#product_group').data('current');
    if (currentGroup) {
        $('#product_group').val(currentGroup).trigger('change');
    }

    // Load ptypes data and set value
    loadPtypesData().then(() => {
        $('#ptype').select2({
            placeholder: 'Select Type',
            width: '100%'
        });

        $('#ptype').on('select2:select', function (e) {
            generateStockCode();
        });

        const form = document.getElementById("editStockForm");
        if (!form) return;

        // Get site from hidden input
        const siteInput = document.getElementById("site");
        const stockSite = siteInput ? siteInput.value : null;
        const filteredPtypes = filterPTypesBySite(stockSite);
        populatePtypeSelect(filteredPtypes);

        // Set the current ptype value after populating
        const currentPtype = $('#ptype').data('current');
        if (currentPtype) {
            $('#ptype').val(currentPtype).trigger('change');
        }

        AutoGenerationListeners();
        initializeLeadingZeroRemoval();
    });
}

if (window.location.pathname.includes("/stocks/add-stock")) {
    initializeStockForm();
} 
if (window.location.pathname.includes("/stocks/edit-stock")) {
    initializeEditStockForm();
}


// Add stock form
// stock code and description generation

// Stock Code Formula
// PType = value
// Caliper = if exist then value up to 4 int digits else none or skipped
// Chipboard No. = if exist then value "#" + up to 4 int digits else none or skipped
// Pounds/Ream = if exist then value up to 4 int digits + "#" else none or skipped
// GSM = if exist then value up to 4 int digits else none or skipped
// Width = if exist then value up to 4 int digits else none or skipped
// Length = if exist then value up to 4 int digits else none or skipped
// UM = if exist then get the first letter of the value

// Stock Code = PType + Caliper + Chipboard No. + Pounds/Ream + GSM +Width + Length + UM
// Example
// PType = BP
// Caliper = 12
// Chipboard No. = 3
// Pounds/Ream = 500
// GSM = 150
// Width = 48
// Length = 96
// UM = Ream

// Stock Code = BP0012#0003500#015000480096R

// Remove leading zeros from number inputs
function removeLeadingZeros(input) {
    let value = input.value.trim();

    // Only process if there's a value and it starts with 0 and has more than 1 character
    if (value && value.startsWith('0') && value.length > 1 && isNaN(value) === false) {
        // Remove leading zeros
        value = value.replace(/^0+/, '');

        // If everything was zeros, keep at least one '0'
        if (value === '' || value === '.') {
            value = '0';
        }

        input.value = value;
    }
}

// Initialize leading zero removal for number inputs
function initializeLeadingZeroRemoval() {
    const numberInputs = document.querySelectorAll(
        '#gsm, #caliper, #pounds_ream, #width, #length'
    );

    numberInputs.forEach(input => {
        // Remove leading zeros on input
        input.addEventListener('input', function() {
            removeLeadingZeros(this);
        });
    });
}


function clearForm() {
    const form = document.getElementById("addStockForm");
    const inputs = form.querySelectorAll(
        'input:not([type="hidden"]):not(#site), select:not(#site)',
    );
    inputs.forEach((input) => {
        if (input.type === "select-one") {
            input.selectedIndex = 0;
        } else {
            input.value = "";
        }
    });
}

function AutoGenerationListeners() {
    // Fields that affect stock code generation
    const generationFields = [
        "ptype",
        "caliper",
        "chipboard_no",
        "pounds_ream",
        "gsm",
        "width",
        "length",
    ];

    generationFields.forEach((fieldId) => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener("input", generateStockCode);
            field.addEventListener("change", generateStockCode);
        }
    });
}

function generateStockCode() {
    // Get form values
    const ptype = document.getElementById("ptype")?.value || "";
    const caliper = document.getElementById("caliper")?.value || "";
    const chipboardNo = document.getElementById("chipboard_no")?.value || "";
    const poundsReam = document.getElementById("pounds_ream")?.value || "";
    const gsm = document.getElementById("gsm")?.value || "";
    const width = document.getElementById("width")?.value || "";
    const length = document.getElementById("length")?.value || "";

    // Only generate if we have at least PType
    if (!ptype || ptype === "Select Type") {
        document.getElementById("ptype").value = "";
        generateStockDescription();
        return;
    }

    let stockCode = ptype;

    // Caliper - up to 4 int digits, zero-padded
    if (caliper) {
        const caliperNum = parseInt(caliper);
        if (!isNaN(caliperNum) && caliperNum > 0) {
            stockCode += caliperNum.toString().padStart(4, "0");
        }
    }

    // Chipboard No. - "#" + up to 4 int digits, zero-padded
    if (chipboardNo) {
        const chipboardNum = parseInt(chipboardNo);
        if (!isNaN(chipboardNum) && chipboardNum > 0) {
            stockCode += "#" + chipboardNum.toString().padStart(4, "0");
        }
    }

    // Pounds/Ream - up to 4 int digits + "#", zero-padded
    if (poundsReam) {
        const poundsReamNum = parseInt(poundsReam);
        if (!isNaN(poundsReamNum) && poundsReamNum > 0) {
            stockCode += poundsReamNum.toString().padStart(4, "0") + "#";
        }
    }

    // GSM - up to 4 int digits, zero-padded
    if (gsm) {
        const gsmNum = parseInt(gsm);
        if (!isNaN(gsmNum) && gsmNum > 0) {
            stockCode += gsmNum.toString().padStart(4, "0");
        }
    }

    // Width - up to 4 int digits, zero-padded
    if (width) {
        const widthNum = parseInt(parseFloat(width));
        if (!isNaN(widthNum) && widthNum > 0) {
            stockCode += widthNum.toString().padStart(4, "0");
        }
    }

    // Length - up to 4 int digits, zero-padded
    if (length) {
        const lengthNum = parseInt(parseFloat(length));
        if (!isNaN(lengthNum) && lengthNum > 0) {
            stockCode += lengthNum.toString().padStart(4, "0");
        }
    }

    // Set the generated stock code
    document.getElementById("stockCode").value = stockCode;

    // Generate stock description
    generateStockDescription();
}

// Stock Description Formula
// PType data-DescLabel = value
// If Caliper exist then add value + " Cal"
// If chipboardNo exist then add " #" + value
// If GSM exist then add value + " GSM"
// If Width and Length exist then add value of width + "x" + length + " "
// If UM exist then add the first letter of UM

function generateStockDescription() {
    const ptype = document.getElementById("ptype")?.value || "";
    const caliper = document.getElementById("caliper")?.value || "";
    const chipboardNo = document.getElementById("chipboard_no")?.value || "";
    const gsm = document.getElementById("gsm")?.value || "";
    const poundsReam = document.getElementById("pounds_ream")?.value || "";
    const width = document.getElementById("width")?.value || "";
    const length = document.getElementById("length")?.value || "";

    let description = "";

    // Get PType DescLabel from data attribute
    const ptypeSelect = document.getElementById("ptype");
    if (ptypeSelect && ptype && ptype !== "Select Type") {
        const selectedOption = ptypeSelect.options[ptypeSelect.selectedIndex];
        const descLabel =
            selectedOption.getAttribute("data-desclabel") || ptype;
        description += descLabel;
    }

    // Add specifications based on formula
    const specs = [];

    // If Caliper exist then add value + " Cal"
    if (caliper) {
        const caliperNum = parseInt(caliper);
        if (!isNaN(caliperNum) && caliperNum > 0) {
            specs.push(caliper + " Cal");
        }
    }

    // If chipboardNo exist then add " #" + value
    if (chipboardNo) {
        const chipboardNum = parseInt(chipboardNo);
        if (!isNaN(chipboardNum) && chipboardNum > 0) {
            specs.push("#" + chipboardNo);
        }
    }

    // If Pounds/Ream exist then add value + " #"
    if (poundsReam) {
        const poundsReamNum = parseInt(poundsReam);
        if (!isNaN(poundsReamNum) && poundsReamNum > 0) {
            specs.push(poundsReam + "#");
        }
    }

    // If GSM exist then add value + " GSM"
    if (gsm) {
        const gsmNum = parseInt(gsm);
        if (!isNaN(gsmNum) && gsmNum > 0) {
            specs.push(gsm + " GSM");
        }
    }

    // If Width and Length exist then add value of width + "x" + length + " "
    if (width && length) {
        const widthNum = parseInt(width);
        const lengthNum = parseInt(length);
        if (!isNaN(widthNum) && !isNaN(lengthNum) && widthNum > 0 && lengthNum > 0) {
            specs.push(widthNum + "\" x " + lengthNum + "\"");
        }
    }

    // Join all parts
    if (specs.length > 0) {
        description += " " + specs.join(" ");
    }

    document.getElementById("stockDescription").value = description;
}

// Handle add stock button click
$("#saveStockbtn").on("click", function (e) {
    e.preventDefault();
    const form = $("#addStockForm")[0];
    const formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            window.location.href = window.appUrl + "/ce/stocks";
            Swal.fire({
                // toast: true,
                // position: "top-end",
                icon: "success",
                title: data.message || "Stock added successfully!",
                showConfirmButton: true,
                // timer: 3000,
                // timerProgressBar: true,
            });
            // $("#addStockModal").modal("hide");
            // form.reset();
            setTimeout(loadStocksTable, 500);
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
                // text: "Please check the form and try again.",
                text: msg,
            });
        },
    });
});

// Handle edit stock form initialization
$("#updateStockbtn").on("click", function (e) {
    e.preventDefault();
    const form = $("#editStockForm")[0];
    const formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            Swal.fire({
                // toast: true,
                // position: "top-end",
                icon: "success",
                title: data.message || "Stock updated successfully!",
                showConfirmButton: true,
                // timer: 3000,
                // timerProgressBar: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = window.appUrl + "/ce/stocks";
                };
            });
            // $("#addStockModal").modal("hide");
            // form.reset();
            // setTimeout(loadStocksTable, 500);
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
                text: msg,
                // text: "Please check the form and try again.",
            });
        },
    });
});

// Handle delete button
$(document).on("click", ".delete-stock-btn", function (e) {
    e.preventDefault();

    const site = $(this).data("site");
    const stockcode = $(this).data("stockcode");
    const stockdesc = $(this).data("stockdesc");

    Swal.fire({
        title: "Delete Stock?",
        html: `
            You are about to delete this stock:<br><br>
            <strong>${stockcode} - ${stockdesc}</strong><br><br>
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
                url: window.appUrl + "/ce/stocks/delete",
                method: "DELETE",
                data: {
                    Site: site,
                    StockCode: stockcode,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (data) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title:
                            data.message || "Stock deleted successfully!",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    setTimeout(loadStocksTable, 500);
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