$.extend($.fn.dataTable.defaults, {
    paging: true,
    info: true,
    lengthChange: false,
    searching: true,
    pageLength: 10,
    responsive: true,
    autoWidth: false,
    order: [],
    layout: {
        topStart: null, // Hides search box
        topEnd: null, // Hides page length / buttons
        bottomStart: "info", // Keep info text
        bottomEnd: "paging", // Keep pagination
    },
    columnDefs: [
        { 
            targets: "_all", 
            type: "string" 
        },
        {
            targets: [-1],
            orderable: false,
            columnControl: [],
        }
    ],
});