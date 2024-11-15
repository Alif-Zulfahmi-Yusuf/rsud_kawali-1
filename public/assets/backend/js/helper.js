let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000
});


$(document).ready(function () {
    // Inisialisasi Select2
    $('.select-single').select2({
        theme: "bootstrap-5", // Gunakan tema Bootstrap 5
        width: '100%', // Menyesuaikan lebar dengan elemen induk
        dropdownCssClass: 'bg-body text-body border', // Menyesuaikan background dan warna teks sesuai tema
        placeholder: $(this).data('placeholder'), // Mengambil placeholder dari atribut data-placeholder
        containerCssClass: 'fs--1', // Ukuran font lebih kecil (sesuaikan dengan ukuran yang diinginkan)
        selectionCssClass: 'form-control form-control-sm', // Memperkecil ukuran input select
        allowClear: true // Menambahkan opsi untuk menghapus pilihan
    });

    // Sesuaikan warna dan font untuk dropdown jika diperlukan
    $('.select-single').on('select2:open', function () {
        $('.select2-dropdown').css({
            'background-color': '#fff', // Sesuaikan background dropdown jika perlu
            'color': '#000', // Sesuaikan warna teks dropdown jika perlu
            'border': '1px solid #ced4da' // Sesuaikan border dropdown jika perlu
        });
    });
});



const toastSuccess = (message) => {
    Toast.fire({
        icon: 'success',
        title: message
    })
}



const toastError = (message) => {
    let resJson;

    try {
        resJson = JSON.parse(message);
    } catch (error) {
        console.log("Error parsing JSON:", error);
        return; // Jika parsing JSON gagal
    }

    let errorText = '';

    // Akses error dari Laravel
    if (resJson.errors) {
        for (let key in resJson.errors) {
            errorText = resJson.errors[key][0]; // Ambil pesan error pertama dari tiap field
            break;
        }
    } else {
        errorText = resJson.message; // Ambil pesan umum jika tidak ada detail
    }

    Toast.fire({
        icon: 'error',
        title: 'Data cannot be saved <br>' + errorText
    });
};


const startLoading = (str = 'Please wait...') => {
    Swal.fire({
        title: 'Loading!',
        text: str,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

const stopLoading = () => {
    Swal.close();
};


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

$(document).ready(function () {
    // Trigger the modal when "Select Atasan" button is clicked
    $('#selectAtasanBtn').on('click', function () {
        $('#atasanModal').modal('show');
    });

    // Select atasan and fill the input fields
    $('.select-atasan').on('click', function () {
        var atasanId = $(this).data('id');
        var atasanName = $(this).data('name');

        // Set the selected atasan's ID and Name in the form
        $('#atasan_id').val(atasanId); // Set the atasan ID to the hidden input
        $('#atasan_name').val(atasanName); // Set the atasan name to the disabled text input

        // Close the modal after selection
        $('#atasanModal').modal('hide');
    });
});




