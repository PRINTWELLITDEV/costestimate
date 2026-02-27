import "bootstrap";
import "admin-lte";

import "./datatables.js";
import "./sites.js";
import "./users.js";
import "./ptypes.js";
import "./vendors.js";
import "./items.js";
import "./pbp.js";


document.addEventListener("DOMContentLoaded", function () {

    const body = document.body;
    const STORAGE_KEY = "adminlte_sidebar_state";

    // 1️⃣ Apply saved state on page load
    const savedState = localStorage.getItem(STORAGE_KEY);
    console.log(savedState);
    
    if (savedState === "open") {
        body.classList.remove("sidebar-collapse");
    } else if (savedState === "collapsed") {
        body.classList.add("sidebar-collapse");
    }

    // 2️⃣ Listen to sidebar toggle click
    const toggleBtn = document.querySelector('[data-lte-toggle="sidebar"]');

    if (toggleBtn) {
        toggleBtn.addEventListener("click", function () {
            // Wait for AdminLTE to toggle class
            setTimeout(function () {
                if (body.classList.contains("sidebar-collapse")) {
                    localStorage.setItem(STORAGE_KEY, "collapsed");
                } else {
                    localStorage.setItem(STORAGE_KEY, "open");
                }
            }, 200);
        });
    }

});
