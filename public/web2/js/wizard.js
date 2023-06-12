Livewire.on('selectButton', id => {
    const button = document.getElementById(id);
    button.classList.contains('someClass');
    button.classList.add('d-none');

    const small = document.getElementById(id + "-sel");
    small.classList.contains('someClass');
    small.classList.remove('d-none');
});

Livewire.on('alertSuccess', function () {
    Swal.fire({
        position: 'bottom-end',
        text: 'Agregado correctamente',
        background: '#7DCEA0',
        showConfirmButton: false,
        timer: 800
    })
});
