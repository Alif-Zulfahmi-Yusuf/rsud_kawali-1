$(document).ready(function () {
    $('#tablePangkat').DataTable({
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

// Contoh penggunaan fungsi dari helper.js
function showSuccessMessage() {
    toastSuccess('Data berhasil ditambahkan!');
}

function showErrorMessage(errorJson) {
    toastError(errorJson);
}

// Menambahkan listener pada tombol untuk memicu notifikasi 
document.addEventListener('DOMContentLoaded', function () {
    if (sessionStorage.getItem('success')) {
        toastSuccess(sessionStorage.getItem('success'));
        sessionStorage.removeItem('success');
    }

    if (sessionStorage.getItem('error')) {
        toastError(JSON.stringify({ errors: { message: sessionStorage.getItem('error') } }));
        sessionStorage.removeItem('error');
    }
});
