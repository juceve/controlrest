//your javascript goes here
var currentTab = 0;
document.addEventListener("DOMContentLoaded", function (event) {

    showTab(currentTab);

});

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Procesar Pago";
    } else {
        document.getElementById("nextBtn").innerHTML = "Siguiente";
    }
   
    fixStepIndicator(n)
}

function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    // if (n == 1 && !validateForm()) return false;
    // if (n == 1) return false;
    x[currentTab].style.display = "none";
    currentTab = currentTab + n;
    if (currentTab >= x.length) {
        // document.getElementById("regForm").submit();
        // return false;
        //alert("sdf");
        // document.getElementById("register").style.display = "none";
        Livewire.emit('regPedido');
    }
    showTab(currentTab);
}

// Livewire.on('finish', function(){
//         document.getElementById("nextprevious").style.display = "none";
//         document.getElementById("all-steps").style.display = "none";        
//         document.getElementById("text-message").style.display = "block";
// });



function validateForm() {
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");
    for (i = 0; i < y.length; i++) {
        if (currentTab == 1) {
            if (y[i].value <= "0") {
                y[i].className += " invalid";
                valid = false;
                Swal.fire(
                    '',
                    'No seleccionó ningun producto.',
                    'error'
                )
            }
        }        
    }
    if (valid) { document.getElementsByClassName("step")[currentTab].className += " finish"; }
    return valid;
}

function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    // for (i = 0; i < x.length; i++) { x[i].className = x[i].className.replace(" active", ""); }
    // x[n].className += " active";
}

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


// Livewire.on('mostrarEstPago', function () {
//     const estudiante = document.getElementById("formEstudiante");
//     estudiante.classList.contains('someClass');
//     estudiante.classList.remove('d-none');

//     const total = document.getElementById("formTotal");
//     total.classList.contains('someClass');
//     total.classList.remove('d-none');

//     const formaPago = document.getElementById("formFormaPago");
//     formaPago.classList.contains('someClass');
//     formaPago.classList.remove('d-none');
// });

// Livewire.on('ocultarEstPago', function () {
//     const estudiante = document.getElementById("formEstudiante");
//     estudiante.classList.contains('someClass');
//     estudiante.classList.add('d-none');

//     const total = document.getElementById("formTotal");
//     total.classList.contains('someClass');
//     total.classList.add('d-none');

//     const formaPago = document.getElementById("formFormaPago");
//     formaPago.classList.contains('someClass');
//     formaPago.classList.add('d-none');
// });

Livewire.on('alertDelete', function () {
    Swal.fire(
        'Elminado!',
        '',
        'success'
    )
    showTab(1);
});

Livewire.on('alertError', function () {
    Swal.fire(
        'Ha ocurrido un error!',
        '',
        'error'
    )
    showTab(1);
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
Livewire.on('alertDanger', function () {
    Swal.fire({
        position: 'center',
        text: 'Codigo incorrecto',
        color: '#943126',
        background: '#F5B7B1',
        showConfirmButton: false,
        timer: 1200
    })
});

Livewire.on('selectButton', id => {
    const button = document.getElementById(id);
    button.classList.contains('someClass');
    button.classList.add('d-none');

    const small = document.getElementById(id + "-sel");
    small.classList.contains('someClass');
    small.classList.remove('d-none');
});
Livewire.on('enableButton', id => {
    const button = document.getElementById(id);
    button.classList.contains('someClass');
    button.classList.remove('d-none');

    const small = document.getElementById(id + "-sel");
    small.classList.contains('someClass');
    small.classList.add('d-none');
});

Livewire.on('actCon', count => {
    document.getElementById('countCart').innerHTML = count;
})   