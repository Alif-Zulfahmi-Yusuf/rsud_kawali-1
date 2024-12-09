$(document).ready(function () {
    var table = $('#tablePerilaku').DataTable({
        order: [], // Tidak ada sorting awal
        columnDefs: [
            { targets: [0], orderable: false }, // Kolom pertama tidak bisa diurutkan
        ],
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

    // Klik grup kategori untuk sorting
    $('#tablePerilaku tbody').on('click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        var groupColumnIndex = 1; // Ganti jika kolom grouping berubah
        if (currentOrder[0] === groupColumnIndex && currentOrder[1] === 'asc') {
            table.order([groupColumnIndex, 'desc']).draw();
        } else {
            table.order([groupColumnIndex, 'asc']).draw();
        }
    });
});


// Fungsi untuk menghapus data dengan konfirmasi SweetAlert
const deleteData = (e) => {
    let uuid = e.getAttribute('data-uuid'); // Mendapatkan data-uuid dari tombol

    if (!uuid) {
        Swal.fire({
            title: "Error!",
            text: "Invalid UUID!",
            icon: "error"
        });
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete this item?",
        icon: "question",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        allowOutsideClick: false,
        showCancelButton: true,
        showCloseButton: true
    }).then((result) => {
        if (result.isConfirmed) { // Jika pengguna mengonfirmasi penghapusan
            startLoading(); // Menampilkan indikator loading (pastikan fungsi ini tersedia)

            $.ajax({
                type: "DELETE",
                url: `/perilaku/destroy/${uuid}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    stopLoading(); // Menghentikan loading indikator
                    toastSuccess(response.message); // Menampilkan notifikasi sukses

                    // Reload DataTable dan hapus baris sesuai UUID
                    var table = $('#tablePerilaku').DataTable(); // Pastikan DataTable sudah diinisialisasi
                    table.rows(`[data-uuid="${uuid}"]`).remove().draw(); // Hapus baris dan redraw tabel
                },
                error: function (xhr, status, error) {
                    stopLoading(); // Menghentikan loading indikator
                    console.error("Error:", error); // Menampilkan pesan error di konsol
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to delete the data. Please try again.",
                        icon: "error",
                        confirmButtonColor: "#3085d6"
                    });
                }
            });
        }
    });
};


