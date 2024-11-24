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
        dropdownCssClass: 'bg-body text-body border fs--1', // Ukuran font lebih kecil pada dropdown
        placeholder: $(this).data('placeholder'), // Placeholder dari atribut data-placeholder
        containerCssClass: 'fs--1', // Ukuran font lebih kecil
        selectionCssClass: 'form-control form-control-sm py-0', // Memperkecil ukuran input select
        allowClear: true // Menambahkan opsi untuk menghapus pilihan
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

    // Delegated event listener for dynamically loaded content
    $('#atasanModal').on('click', '.select-atasan', function () {
        var atasanId = $(this).data('id');
        var atasanName = $(this).data('name');
        var atasanPangkat = $(this).data('pangkat');
        var atasanUnitKerja = $(this).data('unit-kerja');
        var atasanJabatan = $(this).data('jabatan');

        // Set the selected atasan's data into the form inputs
        $('#atasan_id_input').val(atasanId); // Hidden input for atasan ID
        $('#atasan_name').val(atasanName); // Disabled input for atasan name
        $('#atasan_pangkat').val(atasanPangkat); // Disabled input for atasan pangkat
        $('#atasan_unit_kerja').val(atasanUnitKerja); // Disabled input for unit kerja
        $('#atasan_jabatan').val(atasanJabatan); // Disabled input for jabatan

        // Close the modal after selection
        $('#atasanModal').modal('hide');
    });
});









