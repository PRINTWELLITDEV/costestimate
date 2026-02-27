import Swal from "sweetalert2";
import * as bootstrap from "bootstrap";

// Store all data in memory
let allData = {
    vendors: [],
    ptypes: [],
    items: [],
};

// Load all data via AJAX when page loads
function loadAllData() {
    return Promise.all([
        $.get(window.appUrl + "/ce/paper-board-price/api/vendors", (data) => {
            allData.vendors = data;
        }),
        $.get(window.appUrl + "/ce/paper-board-price/api/ptypes", (data) => {
            allData.ptypes = data;
        }),
        $.get(window.appUrl + "/ce/paper-board-price/api/items", (data) => {
            allData.items = data;
        }),
    ]);
}

// Filtering functions
function filterPTypesBySite(siteCode) {
    if (!siteCode) return allData.ptypes;
    return allData.ptypes.filter((p) => p.Site === siteCode);
}
function filterVendorsBySiteAndGroup(siteCode, groupCode) {
    let filtered = allData.vendors;
    if (siteCode) filtered = filtered.filter((v) => v.Site === siteCode);
    if (groupCode) filtered = filtered.filter((v) => v.Group === groupCode);
    return filtered;
}
function filterItemsBySiteAndPType(siteCode, ptypeCode) {
    let filtered = allData.items;
    if (siteCode) filtered = filtered.filter((i) => i.Site === siteCode);
    if (ptypeCode) filtered = filtered.filter((i) => i.PType === ptypeCode);
    return filtered;
}

// Populate select2 dropdowns
function populateSelect2(
    selectId,
    data,
    valueKey,
    displayKey,
    secondaryKey = null,
    placeholderText = "Select...",
) {
    const $select = $(`#${selectId}`);
    const isEditModal = selectId.startsWith("edit_");
    const dropdownParent = isEditModal
        ? $("#editPricingModal")
        : $("#addPricingModal");
    $select.html(`<option disabled selected>${placeholderText}</option>`);
    data.forEach((item) => {
        let displayText = item[displayKey];
        if (secondaryKey) displayText += " - " + item[secondaryKey];
        $select.append(
            $("<option></option>")
                .attr("value", item[valueKey])
                .text(displayText),
        );
    });
    $select.prop("disabled", data.length === 0);
    $select.select2({
        placeholder: placeholderText,
        width: "100%",
        dropdownParent: dropdownParent,
    });
}

// Initialize all select2 dropdowns in the form
function initializeFormSelects(isEditModal = false) {
    const groupId = isEditModal ? "#edit_group" : "#group";
    const vendorId = isEditModal ? "#edit_vendor" : "#vendor";
    const ptypeId = isEditModal ? "#edit_ptype" : "#ptype";
    const itemcodeId = isEditModal ? "#edit_itemcode" : "#itemcode";
    const dropdownParent = isEditModal
        ? $("#editPricingModal")
        : $("#addPricingModal");

    $(groupId)
        .html(
            '<option disabled selected>Select Group</option><option value="IMPORTED">IMPORTED</option><option value="LOCAL">LOCAL</option>',
        )
        .select2({
            placeholder: "Select Group",
            width: "100%",
            dropdownParent: dropdownParent,
        });
    $(ptypeId)
        .html("<option disabled selected>Select Paper Type</option>")
        .select2({
            placeholder: "Select Paper Type",
            width: "100%",
            dropdownParent: dropdownParent,
        });
    $(vendorId)
        .html("<option disabled selected>Select Vendor</option>")
        .select2({
            placeholder: "Select Vendor",
            width: "100%",
            dropdownParent: dropdownParent,
        });
    $(itemcodeId)
        .html("<option disabled selected>Select Item Code</option>")
        .select2({
            placeholder: "Select Item Code",
            width: "100%",
            dropdownParent: dropdownParent,
        });
}

// Load pricing table
function loadPricingList() {
    $.get(window.appUrl + "/ce/paper-board-price/list", function (html) {
        if ($.fn.DataTable.isDataTable("#pricing-table")) {
            $("#pricing-table").DataTable().clear().destroy();
        }
        $("#pricingTableBody").html(html);
        $("#pricing-table").DataTable({
            pageLength: 10,
            fixedHeader: true,
            ordering: { indicators: true, handler: true },
            responsive: true,
            language: { emptyTable: "No data found" },
            orderCellsTop: true,
        });
        $("#pricingSearch").on("keyup", function () {
            $("#pricing-table").DataTable().search(this.value).draw();
        });
    });
}

// Main form logic
function initializePricingForm() {
    initializeFormSelects(false);
    initializeFormSelects(true);

    const hasSiteSelector = $("#site").length > 0;

    if (hasSiteSelector) {
        const site = $("#site").val() || $('input#site').val();
        const filteredPTypes = filterPTypesBySite(site);
        populateSelect2(
            "ptype",
            filteredPTypes,
            "PType",
            "PType",
            "PTypeDesc",
            "Select Paper Type"
        );

        $("#site").on("change", function () {
            const selectedSite = $(this).val();
            if (selectedSite) {
                const filteredPTypes = filterPTypesBySite(selectedSite);
                if (filteredPTypes.length > 0) {
                    populateSelect2(
                        "ptype",
                        filteredPTypes,
                        "PType",
                        "PType",
                        "PTypeDesc",
                        "Select Paper Type",
                    );
                    $("#ptype").prop("disabled", false);
                } else {
                    $("#ptype")
                        .html(
                            "<option disabled selected>No Paper Type Available</option>",
                        )
                        .prop("disabled", true)
                        .select2({
                            placeholder: "No Paper Type Available",
                            width: "100%",
                            dropdownParent: $("#addPricingModal"),
                        });
                }
                $("#group").html(
                    '<option disabled selected>Select Group</option><option value="IMPORTED">IMPORTED</option><option value="LOCAL">LOCAL</option>',
                );
                $("#vendor")
                    .html(
                        "<option disabled selected>Select Group First</option>",
                    )
                    .prop("disabled", true)
                    .select2({
                        placeholder: "Select Group First",
                        width: "100%",
                        dropdownParent: $("#addPricingModal"),
                    });
                $("#itemcode")
                    .html(
                        "<option disabled selected>Select Paper Type First</option>",
                    )
                    .prop("disabled", true)
                    .select2({
                        placeholder: "Select Paper Type First",
                        width: "100%",
                        dropdownParent: $("#addPricingModal"),
                    });
                $("#currcode").val("");
            } else {
                $("#group").html(
                    "<option disabled selected>Select Site First</option>",
                );
                $("#ptype")
                    .html(
                        "<option disabled selected>Select Site First</option>",
                    )
                    .prop("disabled", true)
                    .select2({
                        placeholder: "Select Site First",
                        width: "100%",
                        dropdownParent: $("#addPricingModal"),
                    });
                $("#vendor")
                    .html(
                        "<option disabled selected>Select Site First</option>",
                    )
                    .prop("disabled", true)
                    .select2({
                        placeholder: "Select Site First",
                        width: "100%",
                        dropdownParent: $("#addPricingModal"),
                    });
                $("#itemcode")
                    .html(
                        "<option disabled selected>Select Site First</option>",
                    )
                    .prop("disabled", true)
                    .select2({
                        placeholder: "Select Site First",
                        width: "100%",
                        dropdownParent: $("#addPricingModal"),
                    });
                $("#currcode").val("");
            }
        });
    }

    // Group to Vendor
    $(document).on("change", "#group, #edit_group", function () {
        const selectedGroup = $(this).val();
        const isEditModal = $(this).attr("id") === "edit_group";
        const vendorSelectId = isEditModal ? "edit_vendor" : "vendor";
        const currCodeId = isEditModal ? "edit_currcode" : "currcode";
        const dropdownParent = isEditModal
            ? $("#editPricingModal")
            : $("#addPricingModal");

        let selectedSite;
        if ($("#site").length) selectedSite = $("#site").val();
        if ($("#edit_site").val()) selectedSite = $("#edit_site").val();

        if (selectedGroup && selectedSite) {
            const filteredVendors = filterVendorsBySiteAndGroup(
                selectedSite,
                selectedGroup,
            );
            if (filteredVendors.length > 0) {
                populateSelect2(
                    vendorSelectId,
                    filteredVendors,
                    "Vendnum",
                    "Vendnum",
                    "Name",
                    "Select Vendor",
                );
                $(`#${vendorSelectId}`).prop("disabled", false);
            } else {
                $(`#${vendorSelectId}`)
                    .html(
                        "<option disabled selected>No Vendor Available</option>",
                    )
                    .prop("disabled", true)
                    .select2({
                        placeholder: "No Vendor Available",
                        width: "100%",
                        dropdownParent: dropdownParent,
                    });
            }
        } else {
            let placeholderText = "Select Group First";
            if (!selectedSite) placeholderText = "Select Site First";
            $(`#${vendorSelectId}`)
                .html(`<option disabled selected>${placeholderText}</option>`)
                .prop("disabled", true)
                .select2({
                    placeholder: placeholderText,
                    width: "100%",
                    dropdownParent: dropdownParent,
                });
        }
        $(`#${currCodeId}`).val("");
    });

    // PType to ItemCode
    $(document).on("select2:select", "#ptype, #edit_ptype", function () {
        const selectedPType = $(this).val();
        const isEditModal = $(this).attr("id") === "edit_ptype";
        const selectIdForItems = isEditModal ? "edit_itemcode" : "itemcode";
        const dropdownParent = isEditModal
            ? $("#editPricingModal")
            : $("#addPricingModal");

        let selectedSite;
        if ($("#site").length) selectedSite = $("#site").val();
        if ($("#edit_site").val()) selectedSite = $("#edit_site").val();

        if (selectedPType && selectedSite) {
            const filteredItems = filterItemsBySiteAndPType(
                selectedSite,
                selectedPType,
            );
            if (filteredItems.length > 0) {
                populateSelect2(
                    selectIdForItems,
                    filteredItems,
                    "ItemCode",
                    "ItemCode",
                    "ItemDesc",
                    "Select Item Code",
                );
                $(`#${selectIdForItems}`).prop("disabled", false);
            } else {
                $(`#${selectIdForItems}`)
                    .html(
                        "<option disabled selected>No Item Code Available</option>",
                    )
                    .prop("disabled", true)
                    .select2({
                        placeholder: "No Item Code Available",
                        width: "100%",
                        dropdownParent: dropdownParent,
                    });
            }
        } else {
            if (!selectedSite) {
                $(`#${selectIdForItems}`)
                    .html(
                        "<option disabled selected>Select Site First</option>",
                    )
                    .prop("disabled", true)
                    .select2({
                        placeholder: "Select Site First",
                        width: "100%",
                        dropdownParent: dropdownParent,
                    });
            } else {
                $(`#${selectIdForItems}`)
                    .html(
                        "<option disabled selected>Select Paper Type First</option>",
                    )
                    .prop("disabled", true)
                    .select2({
                        placeholder: "Select Paper Type First",
                        width: "100%",
                        dropdownParent: dropdownParent,
                    });
            }
        }
    });

    // Vendor to Currency
    $(document).on("select2:select", "#vendor, #edit_vendor", function () {
        const vendnum = $(this).val();
        const isEditModal = $(this).attr("id") === "edit_vendor";
        const currCodeId = isEditModal ? "edit_currcode" : "currcode";
        let site;
        if ($("#site").length) site = $("#site").val();
        if ($("#edit_site").val()) site = $("#edit_site").val();

        if (vendnum && site) {
            const vendor = allData.vendors.find(
                (v) => v.Site === site && v.Vendnum === vendnum,
            );
            $(`#${currCodeId}`).val(vendor ? vendor.Currcode : "");
        } else {
            $(`#${currCodeId}`).val("");
        }
    });
}

// --- Not needed for main logic, comment out ---
// function populateSelect() { /* ...not used... */ }
// ------------------------------------------------

// Main initialization
if (window.location.pathname.includes("/paper-board-price")) {
    loadAllData().then(() => {
        loadPricingList();
        initializePricingForm();
    });
}

// Set CSRF token for all AJAX requests
$.ajaxSetup({
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
});

// Handle add pricing button click
$("#savePricingBtn").on("click", function (e) {
    e.preventDefault();
    const form = $("#addPricingForm")[0];
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
                title: data.message || "Pricing added successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#addPricingModal").modal("hide");
            form.reset();
            setTimeout(loadPricingList, 500);
        },
        error: function (xhr) {
            let msg = "An error occurred";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join(", ");
            }
            Swal.fire({
                icon: "error",
                title: msg,
                text: "Please check the form and try again.",
            });
        },
    });
});

// Clear Add pricing modal fields when closed
$("#addPricingModal").on("hidden.bs.modal", function () {
    $(this).find("form")[0].reset();
    if ($("#ptype").hasClass("select2-hidden-accessible"))
        $("#ptype").select2("destroy");
    if ($("#vendor").hasClass("select2-hidden-accessible"))
        $("#vendor").select2("destroy");
    if ($("#group").hasClass("select2-hidden-accessible"))
        $("#group").select2("destroy");
    if ($("#itemcode").hasClass("select2-hidden-accessible"))
        $("#itemcode").select2("destroy");
    initializeFormSelects(false);
});

// Handle edit button click
$(document).on("click", ".edit-pricing-btn", function (e) {
    e.preventDefault();
    const row = $(this).closest("tr");
    const site = row.data("site");
    const id = row.data("id");

    $("#edit_id").val(id);
    $("#edit_site").val(site);

    initializeFormSelects(true);

    const selectedGroup = row.data("vendorgroup") || row.data("group");
    $("#edit_group").val(selectedGroup);

    const filteredPTypes = filterPTypesBySite(site);
    populateSelect2(
        "edit_ptype",
        filteredPTypes,
        "PType",
        "PType",
        "PTypeDesc",
        "Select Paper Type",
    );

    const filteredVendors = filterVendorsBySiteAndGroup(site, selectedGroup);
    populateSelect2(
        "edit_vendor",
        filteredVendors,
        "Vendnum",
        "Vendnum",
        "Name",
        "Select Vendor",
    );

    const selectedPType = row.data("ptype");
    const filteredItems = filterItemsBySiteAndPType(site, selectedPType);
    populateSelect2(
        "edit_itemcode",
        filteredItems,
        "ItemCode",
        "ItemCode",
        "ItemDesc",
        "Select Item Code",
    );

    setTimeout(() => {
        $("#edit_group").val(selectedGroup).trigger("change");
        $("#edit_ptype").val(selectedPType).trigger("change");
        $("#edit_vendor").val(row.data("vendor")).trigger("change");
        $("#edit_itemcode").val(row.data("itemcode")).trigger("change");
        $("#edit_currcode").val(row.data("currcode"));
    }, 100);

    const effectiveDate = row.data("effectivedate");
    if (effectiveDate) {
        const dateParts = effectiveDate.split("/");
        if (dateParts.length === 3) {
            const formattedDate = `${dateParts[2]}-${dateParts[0].padStart(2, "0")}-${dateParts[1].padStart(2, "0")}`;
            $("#edit_effectivedate").val(formattedDate);
        }
    }
    $("#edit_price_mt").val(row.data("price_mt"));
    $("#edit_price_sheet").val(row.data("price_sheet"));
    $("#edit_price_pound").val(row.data("price_pound"));
    $("#edit_price_bale").val(row.data("price_bale"));

    const editModal = new bootstrap.Modal(
        document.getElementById("editPricingModal"),
    );
    editModal.show();
});

// Clear Edit modal fields when closed
$("#editPricingModal").on("hidden.bs.modal", function () {
    $(this).find("form")[0].reset();
    if ($("#edit_ptype").hasClass("select2-hidden-accessible"))
        $("#edit_ptype").select2("destroy");
    if ($("#edit_vendor").hasClass("select2-hidden-accessible"))
        $("#edit_vendor").select2("destroy");
    if ($("#edit_group").hasClass("select2-hidden-accessible"))
        $("#edit_group").select2("destroy");
    if ($("#edit_itemcode").hasClass("select2-hidden-accessible"))
        $("#edit_itemcode").select2("destroy");
    initializeFormSelects(true);
});

// Handle update pricing button click
$("#updatePricingBtn").on("click", function (e) {
    e.preventDefault();
    const form = $("#editPricingForm")[0];
    const formData = new FormData(form);

    $.ajax({
        url: $(form).attr("action"),
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: { "X-HTTP-Method-Override": "PUT" },
        success: function (data) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: data.message || "Pricing updated successfully!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            $("#editPricingModal").modal("hide");
            setTimeout(loadPricingList, 500);
        },
        error: function (xhr) {
            let msg = "An error occurred";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join(", ");
            }
            Swal.fire({
                icon: "error",
                title: msg,
                text: "Please check the form and try again.",
            });
        },
    });
});

// Handle delete button click
$(document).on("click", ".delete-pricing-btn", function (e) {
    e.preventDefault();

    const site = $(this).data("site");
    const pricingId = $(this).data("id");
    const ptype = $(this).data("ptype");
    const vendor = $(this).data("vendor");
    const itemcode = $(this).data("itemcode");

    Swal.fire({
        title: "Delete this pricing?",
        html: `
            You are about to delete this pricing:<br><br>
            Paper Type: <strong>${ptype}</strong><br>
            Vendor: <strong>${vendor}</strong><br>
            Item Code: <strong>${itemcode}</strong><br><br>
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
                url: window.appUrl + "/ce/paper-board-price/delete",
                method: "DELETE",
                data: {
                    Site: site,
                    PricingId: pricingId,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (data) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: data.message || "Pricing deleted successfully!",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    setTimeout(loadPricingList, 500);
                },
                error: function (xhr) {
                    let msg = "An error occurred while deleting the pricing.";
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
