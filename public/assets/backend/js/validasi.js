$(document).ready(function () {
    var groupColumn = 1; // Kolom kedua (indeks 1) untuk pengelompokan
    var table = $('#tableValidasi').DataTable({
        columnDefs: [
            { visible: false, targets: groupColumn } // Sembunyikan kolom Tahun
        ],
        order: [[groupColumn, 'asc']], // Urutkan berdasarkan Tahun
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;

            api.column(groupColumn, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="8" class="fw-bold bg-light text-center">' + group +
                            '</td></tr>'
                        );
                        last = group;
                    }
                });
        },
        language: {
            emptyTable: "Tidak ada data yang tersedia",
            info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 hingga 0 dari 0 data",
            lengthMenu: "Tampilkan _MENU_ data",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Event klik pada grup untuk mengurutkan data
    $('#tableValidasi tbody').on('click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
            table.order([groupColumn, 'desc']).draw();
        } else {
            table.order([groupColumn, 'asc']).draw();
        }
    });
});