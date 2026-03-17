import Swal from "sweetalert2";

if (window.location.pathname.includes("/paper-board-price/calculator")) {
    $(function () {
        const MM_PER_INCH = 25.4;

        function toNumber(v) {
            const n = parseFloat(v);
            return Number.isFinite(n) ? n : null;
        }

        function fmt(v) {
            return Number(v).toFixed(3);
        }

        function syncMmFromIn() {
            const inL = toNumber($("#sheet_in_l").val());
            const inW = toNumber($("#sheet_in_w").val());

            $("#sheet_mm_l").val(inL === null ? "" : fmt(inL * MM_PER_INCH));
            $("#sheet_mm_w").val(inW === null ? "" : fmt(inW * MM_PER_INCH));
        }

        function syncInFromMm() {
            const mmL = toNumber($("#sheet_mm_l").val());
            const mmW = toNumber($("#sheet_mm_w").val());

            $("#sheet_in_l").val(mmL === null ? "" : fmt(mmL / MM_PER_INCH));
            $("#sheet_in_w").val(mmW === null ? "" : fmt(mmW / MM_PER_INCH));
        }

        function fillStockInfo() {
            const $selected = $("#stock_code option:selected");

            if (!$selected.length || !$selected.val()) {
                $(
                    "#stock_desc, #p_type, #gsm, #sheet_in_l, #sheet_in_w, #sheet_mm_l, #sheet_mm_w",
                ).val("");
                return;
            }

            const stockDesc = $selected.data("stockdesc") ?? "";
            const pType = $selected.data("ptype") ?? "";
            const gsm = $selected.data("gsm") ?? "";
            const width = $selected.data("width");
            const length = $selected.data("length");

            $("#stock_desc").val(stockDesc);
            $("#p_type").val(pType);
            $("#gsm").val(gsm);

            // if (length !== undefined && length !== null && length !== "" &&
            //     width !== undefined && width !== null && width !== "") {
            //     $("#sheet_in_l").val(length);
            //     $("#sheet_in_w").val(width);
            //     syncMmFromIn();
            // } else {
            //     $("#sheet_in_l, #sheet_in_w, #sheet_mm_l, #sheet_mm_w").val("");
            // }
        }

        let allStocks = [];

        function initStockSelect2() {
            if ($("#stock_code").hasClass("select2-hidden-accessible")) {
                $("#stock_code").select2("destroy");
            }

            $("#stock_code").select2({
                width: "100%",
                placeholder: "Select Stock",
                allowClear: true,
            });
        }

        function setStockPlaceholder(text) {
            $("#stock_code")
                .html(`<option value="" selected disabled>${text}</option>`)
                .trigger("change.select2");
        }

        function loadStocks() {
            return $.get(
                window.appUrl + "/ce/paper-board-price/api/stocks",
                function (data) {
                    allStocks = Array.isArray(data) ? data : [];
                },
            );
        }

        function populateStocksBySite(siteCode) {
            const $stock = $("#stock_code");

            if (!siteCode) {
                setStockPlaceholder("Select Site First");
                $stock.prop("disabled", true);
                initStockSelect2();
                fillStockInfo();
                return;
            }

            const filtered = allStocks.filter(
                (s) => String(s.Site || "").trim() === String(siteCode).trim(),
            );

            if (filtered.length === 0) {
                setStockPlaceholder("No StockCode Available");
                $stock.prop("disabled", true);
                initStockSelect2();
                fillStockInfo();
                return;
            }

            let html =
                '<option value="" selected disabled>Select Stock</option>';
            filtered.forEach((s) => {
                html += `
                <option value="${s.StockCode}"
                    data-stockdesc="${s.StockDesc ?? ""}"
                    data-ptype="${s.PType ?? ""}"
                    data-gsm="${s.GSM ?? ""}"
                    data-width="${s.Width ?? ""}"
                    data-length="${s.Length ?? ""}">
                    ${s.StockCode}
                </option>
            `;
            });

            $stock.html(html).prop("disabled", false);
            initStockSelect2();
            fillStockInfo();
        }

        $(document).on("change", "#stock_code", fillStockInfo);

        // MM -> IN
        $(document).on("input", "#sheet_mm_l, #sheet_mm_w", function () {
            syncInFromMm();
        });

        // IN -> MM
        $(document).on("input", "#sheet_in_l, #sheet_in_w", function () {
            syncMmFromIn();
        });

        // Initialize site/stock dependency
        const isLevel1 =
            Number($("#pbpCalculatorForm").data("user-level")) === 1;

        loadStocks().then(function () {
            if (isLevel1) {
                $("#stock_code").prop("disabled", true);
                initStockSelect2();

                $(document).on("change", "#site", function () {
                    populateStocksBySite($(this).val());
                });
            } else {
                const siteValue = $("#site_hidden").val();
                populateStocksBySite(siteValue);
            }
        });

        fillStockInfo();

        function num(v, d = 2) {
            const n = Number(v || 0);
            return n.toLocaleString(undefined, {
                minimumFractionDigits: d,
                maximumFractionDigits: d,
            });
        }

        function recalculateRow($tr) {
            // Inputs from top form
            const fxRate = Number($('input[name="FXRate"]').val() || 0);
            const dutyRatePct = Number($('input[name="DutyRate"]').val() || 0);
            const otherChargesPct = Number(
                $('input[name="OtherChargesRate"]').val() || 0,
            );
            const sheetingInput = Number(
                $('input[name="SheetingCost"]').val() || 0,
            );

            // Row/base data
            const priceMT = Number($tr.data("price-mt") || 0);
            const gsm = Number($tr.data("gsm") || 0);
            const um = String($tr.data("um") || "");
            const sheetMM_L = Number($("#sheet_mm_l").val() || 0);
            const sheetMM_W = Number($("#sheet_mm_w").val() || 0);

            const excludeDuty = $tr
                .find(".js-excludeduty-toggle")
                .is(":checked");
            const applySheeting =
                um === "RL" ||
                (um === "SH" && $tr.find(".js-sheeting-toggle").is(":checked"));

            // same as calculatePrice()
            const CFCostInPesos = priceMT * fxRate;
            const DutyRate = excludeDuty ? 0 : dutyRatePct / 100;
            const DutyAmount = CFCostInPesos * DutyRate;
            const OtherCharges = CFCostInPesos * (otherChargesPct / 100);
            const LandedCost = CFCostInPesos + DutyAmount + OtherCharges;
            const SheetingCost = applySheeting ? sheetingInput : 0;
            const SheetedCost = LandedCost + SheetingCost;

            const SheetSizeIn_L = sheetMM_L / 25.4;
            const SheetSizeIn_W = sheetMM_W / 25.4;
            const AreaInSqIn = SheetSizeIn_L * SheetSizeIn_W;
            const AreaInSqMM = AreaInSqIn / 1550;
            const GramsPerSheet = AreaInSqMM * gsm;
            const SheetsPerMT = GramsPerSheet > 0 ? 1000000 / GramsPerSheet : 0;
            const CostPerSheet =
                SheetsPerMT > 0 ? SheetedCost / SheetsPerMT : 0;

            // write back cells
            $tr.find(".js-cf-pesos").text(num(CFCostInPesos, 2));
            $tr.find(".js-duty-rate").text(num(DutyRate * 100, 2) + "%");
            $tr.find(".js-duty-amount").text(num(DutyAmount, 2));
            $tr.find(".js-other-charges").text(num(OtherCharges, 2));
            $tr.find(".js-landed-cost").text(num(LandedCost, 2));
            $tr.find(".js-sheeting-cost").text(num(SheetingCost, 2));
            $tr.find(".js-sheeted-cost").text(num(SheetedCost, 2));
            $tr.find(".js-area-sqin").text(num(AreaInSqIn, 2));
            $tr.find(".js-area-sqmm").text(num(AreaInSqMM, 8));
            $tr.find(".js-grams-per-sheet").text(num(GramsPerSheet, 8));
            $tr.find(".js-sheets-per-mt").text(num(SheetsPerMT, 0));
            $tr.find(".js-cost-per-sheet")
                .text(num(CostPerSheet, 4))
                .attr("data-value", CostPerSheet);
        }

        function renderCalculatorRows(rows) {
            const $tbody = $("#pbpCalculatorTbody");
            if (!rows || rows.length === 0) {
                $tbody.html(
                    '<tr><td colspan="21" class="text-muted">No matching pricing found.</td></tr>',
                );
                return;
            }

            let html = "";
            rows.forEach((r) => {
                const shToggleEnabled = r.UM === "SH";
                const rlChecked = r.UM === "RL" ? "checked disabled" : "";
                html += `
                <tr data-um="${r.UM}" data-price-mt="${r.Price_MT}" data-gsm="${r.GSM}">
                    <td><input class="form-check-input js-row-select" type="checkbox"></td>
                    <td class="text-start">${r.VendorName ?? r.Vendor ?? ""}</td>
                    <td>${r.UM ?? ""}</td>
                    <td>${num(r.GSM, 0)}</td>
                    <td>${num(r.Price_MT, 2)}</td>
                    <td>${num(r.SheetIN_L, 5)}</td>
                    <td>${num(r.SheetIN_W, 5)}</td>
                    <td class="bg-danger-subtle fw-semibold js-cost-per-sheet" data-value="${Number(r.CostPerSheet || 0)}">${num(r.CostPerSheet, 4)}</td>

                    <td class="js-cf-pesos">${num(r.CFCostInPesos, 2)}</td>
                    <td class="js-duty-rate">${num(r.DutyRate, 2)}%</td>
                    <td class="js-duty-amount">${num(r.DutyAmount, 2)}</td>
                    <td class="js-other-charges">${num(r.OtherCharges, 2)}</td>
                    <td class="js-landed-cost">${num(r.LandedCost, 2)}</td>
                    <td class="text-danger fw-semibold js-sheeting-cost">${num(r.SheetingCost, 2)}</td>
                    <td class="js-sheeted-cost">${num(r.SheetedCost, 2)}</td>

                    <td class="js-area-sqin">${num(r.AreaInSqIn, 2)}</td>
                    <td class="js-area-sqmm">${num(r.AreaInSqMM, 8)}</td>
                    <td class="js-grams-per-sheet">${num(r.GramsPerSheet, 8)}</td>
                    <td class="js-sheets-per-mt">${num(r.SheetsPerMT, 0)}</td>
                    ${shToggleEnabled ? '<td><input class="form-check-input js-sheeting-toggle" name="ApplySheeting" type="checkbox"></td>' : "<td></td>"}
                    <td><input class="form-check-input js-excludeduty-toggle" name="ExcludeDuty" type="checkbox"></td>
                </tr>
            `;
            });

            $tbody.html(html);
            $("#updateCost").prop("disabled", true);
        }

        // Only one row can be checked at a time
        $(document).on("change", ".js-row-select", function () {
            if (this.checked) {
                $(".js-row-select").not(this).prop("checked", false);
            }
            $("#updateCost").prop(
                "disabled",
                $(".js-row-select:checked").length === 0,
            );
        });

        $(document).on("change", ".js-sheeting-toggle", function () {
            const $tr = $(this).closest("tr");
            recalculateRow($tr);
        });

        $(document).on("change", ".js-excludeduty-toggle", function () {
            const $tr = $(this).closest("tr");
            recalculateRow($tr);
        });

        // Update Paper Cost button -> put selected row CostPerSheet into top form field
        $(document).on("click", "#updateCost", function () {
            const $checked = $(".js-row-select:checked").first();
            if (!$checked.length) return;

            const $row = $checked.closest("tr");
                
            const raw = Number($row.find(".js-cost-per-sheet").attr("data-value") || 0);

            $('input[name="CostPerSheet"]').val(raw.toFixed(4));
        });

        // optional: if rates change, recalc all visible rows
        // $(document).on("input", 'input[name="FXRate"], input[name="DutyRate"], input[name="OtherChargesRate"], input[name="SheetingCost"], #sheet_mm_l, #sheet_mm_w', function () {
        //     $("#pbpCalculatorTbody tr").each(function () {
        //         if ($(this).find(".js-cost-per-sheet").length) {
        //             recalculateRow($(this));
        //         }
        //     });
        // });

        $(document).on("submit", "#pbpCalculatorForm", function (e) {
            e.preventDefault();

            const $form = $(this);
            $.ajax({
                url: $form.attr("action"),
                method: "POST",
                data: $form.serialize(),
                success: function (res) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: res.message || "Calculation successful.",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });
                    renderCalculatorRows(res.result || []);
                },
                error: function (xhr) {
                    const msg =
                        xhr.responseJSON?.message || "Calculation failed.";
                    // $("#pbpCalculatorTbody").html(`<tr><td colspan="20" class="text-danger">${msg}</td></tr>`);
                    Swal.fire({
                        icon: "error",
                        title: msg,
                        text: "Please check the form and try again.",
                    });
                },
            });
        });
    });
}
