$(document).ready(function () {
    // Inisialisasi DataTable dengan opsi bahasa
    window.tablePangkat = $('#tableEvaluasi').DataTable({
        language: {
            emptyTable: "<div style='text-align: center;'>Data Masih Kosong</div>",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            lengthMenu: "",
            search: "",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        searching: false
    });
});