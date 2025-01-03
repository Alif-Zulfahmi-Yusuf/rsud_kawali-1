$(document).ready(function () {
    // Inisialisasi DataTable dengan opsi bahasa
    window.tablePangkat = $("#tableEvaluasi").DataTable({
        language: {
            emptyTable:
                "<div style='text-align: center;'>Data Masih Kosong</div>",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            lengthMenu: "",
            search: "",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous",
            },
        },
        searching: false,
    });
});

const deleteData = (e) => {
    let uuid = e.getAttribute("data-uuid"); // Mendapatkan data-uuid

    if (!uuid) {
        Swal.fire({
            title: "Error!",
            text: "Invalid UUID!",
            icon: "error",
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
        showCloseButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengonfirmasi penghapusan
            startLoading(); // Menampilkan loading indikator (pastikan fungsi ini sudah diimplementasi)

            $.ajax({
                type: "DELETE",
                url: `/evaluasi-pegawai/destroy/${uuid}`,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    stopLoading(); // Menghentikan loading indikator
                    toastSuccess(response.message); // Menampilkan notifikasi sukses

                    // Hapus baris dari DataTable
                    var table = $("#tableEvaluasi").DataTable();
                    table.rows().every(function () {
                        var row = this.node();
                        if ($(row).data("uuid") === uuid) {
                            table.row(row).remove(); // Hapus baris dari tabel
                        }
                    });
                    table.draw(); // Redraw tabel untuk memperbarui tampilan
                },
                error: function (xhr, status, error) {
                    stopLoading(); // Menghentikan loading indikator jika ada error
                    console.error("Error:", error); // Menampilkan pesan error di konsol
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to delete the data. Please try again.",
                        icon: "error",
                        confirmButtonColor: "#3085d6",
                    });
                },
            });
        }
    });
};

// Fungsi untuk menghapus baris dari DataTable
const removeRowFromTable = (uuid) => {
    var table = $("#tableEvaluasi").DataTable();
    table.rows().every(function () {
        var row = this.node();
        if ($(row).data("uuid") === uuid) {
            table.row(row).remove(); // Hapus baris dari tabel
        }
    });
    table.draw(); // Redraw DataTable untuk memperbarui tampilan
};


document.addEventListener('DOMContentLoaded', function() {
    // Buka modal dengan data
    document.querySelectorAll('.upload-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const evaluasiId = this.getAttribute('data-evaluasi-id');
            const rencanaId = this.getAttribute('data-rencana-id');

            // Set data ke modal
            document.getElementById('evaluasi_pegawai_id').value = evaluasiId;
            document.getElementById('rencana_pegawai_id').value = rencanaId;

            // Tampilkan modal
            const uploadFileModal = new bootstrap.Modal(document.getElementById(
                'uploadFileModal'));
            uploadFileModal.show();
        });
    });

    // Proses upload file
    document.getElementById('uploadFileForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("realisasi.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'File berhasil diupload!',
                }).then(() => {
                    location.reload();
                });
            } else {
                const error = await response.json();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan.',
                });
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Tidak dapat menghubungi server.',
            });
        }
    });
});