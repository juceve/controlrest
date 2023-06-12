function remCartMenu(id, nombre) {
    Swal.fire({
        title: 'Eliminar ' + nombre + '?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.emit('remCart', id);
        }
    })
}
Livewire.on('alertWarning', msg => {
    Swal.fire(
        'Alerta!',
        msg,
        'warning'
    );
});

Livewire.on('alertDelete', function () {
    Swal.fire({
        position: 'bottom-end',
        text: 'Producto eliminado',
        background: '#EC7063',
        color: '#FDEDEC',
        showConfirmButton: false,
        timer: 800
    })
});