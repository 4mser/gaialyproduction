

function toast(type, text) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    })

    Toast.fire({
        icon: type,
        title: text
    })
}

function confirm(id, message, toEmit) {
    Swal.fire({
        text: message,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#27b1bb",
        confirmButtonText: "Yes",
        cancelButtonText: "No"
    }).then((result) => {
        if (result.value) {
            Livewire.emit(toEmit, id);
        }
    })
}

function alert(message, type = 'info') {
    Swal.fire({
        title: message,
        icon: type,
        confirmButtonColor: "#27b1bb",
        confirmButtonText: "Accept",
    })
}

window.addEventListener('alert', (e) => {
    alert(e.detail.message, e.detail.type);
});

window.addEventListener('toast', (e) => {
    toast(e.detail.type, e.detail.message);
});