$(document).ready(function () {
    $('#tableRoles').DataTable({
        // Opsi DataTables yang diinginkan
        language: {
            emptyTable: "No data available in table",
            info: "Show _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Show 0 to 0 of 0 entries",
            lengthMenu: "Show _MENU_ entries",
            search: "Cari:",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});