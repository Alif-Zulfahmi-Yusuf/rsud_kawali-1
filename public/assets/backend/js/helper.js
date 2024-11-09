let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

const toastSuccess = (status) => {
    Toast.fire({
        icon: 'success',
        title: status
    });
};

const toastError = (status) => {
    let resJson = JSON.parse(status); // Mengonversi JSON untuk mendapatkan pesan error
    let errorText = '';
    for (let key in resJson.errors) {
        errorText = resJson.errors[key];
        break; // Ambil pesan error pertama dari array
    }

    Toast.fire({
        icon: 'error',
        title: 'Ops! Data Tidak Valid <br>' + errorText
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

