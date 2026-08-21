<script>

const SwalModal = (icon, title, html, imageUrl, footer) => {
    Swal.fire({
        icon,
        title,
        imageUrl,
        footer,
        imageWidth: 100,
        imageHeight: 100,
        html,

          showClass: {
            popup: `
              animate__animated
              animate__fadeInDown
              animate__faster
            `
          },
          hideClass: {
            popup: `
              animate__animated
              animate__fadeOutDown
              animate__faster
            `
          },
        backdrop: `
            rgba(0,0,123,0.4)
            left top
            no-repeat
          `
    })
}

const SwalConfirm = (icon, title, html, confirmButtonText, method, params, callback) => {
    Swal.fire({
        icon,
        title,
        html,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText,
        backdrop: `
            rgba(0,0,123,0.4)
            left top
            no-repeat
          `
    }).then(result => {
        if (result.value) {
            if (Array.isArray(params) && params.length > 1) {
                return livewire.emit(method, ...params);
            } else {
                return livewire.emit(method, params);
            }
        }

        if (callback) {
            return livewire.emit(callback)
        }
    })
}


const SwalConfirmText = async (
    icon,
    title,
    html,
    confirmButtonText,
    method,
    params,
    callback
) => {

    const palabras = [
        'Confirmar',
        'Aceptar',
        'Continuar',
        'Proceder',
        'Autorizar',
        'Validar',
        'Aprobar',
        'Confirm',
        'Accept',
        'Continue',
        'Proceed',
        'Authorize',
        'Validate',
        'Approve'
    ];

    const palabra = palabras[
        Math.floor(Math.random() * palabras.length)
    ];

    const { value: confirmacion } = await Swal.fire({

        icon: icon,

        title: title,

        html: `
            ${html}

            <p class="mt-3">
                Para continuar escribe:
            </p>

            <strong style="font-size: 20px;">
                ${palabra}
            </strong>
        `,

        input: 'text',

        inputPlaceholder: `Escribe ${palabra}`,

        inputAttributes: {
            autocomplete: 'off',
            autocapitalize: 'off'
        },
        backdrop: `
            rgba(0,0,123,0.4)
            left top
            no-repeat
        `,
        showCancelButton: true,
        focusConfirm: false,

        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Cancelar',

        inputValidator: (value) => {

            if (!value) {
                return 'Debes escribir la palabra de confirmación';
            }

            if (value.trim() !== palabra) {
                return `Debes escribir exactamente: ${palabra}`;
            }
        }
    });

    if (confirmacion) {

        if (Array.isArray(params)) {
            return livewire.emit(method, ...params);
        }

        return livewire.emit(method, params);
    }

    if (callback) {
        return livewire.emit(callback);
    }
};

const SwalAlert = (icon, title, timeout = 4000) => {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: timeout,
        onOpen: toast => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
    })

    Toast.fire({
        icon,
        title
    })
}

const SwalInput = (title, input, inputOptions, inputPlaceholder, showCancelButton, getId, method) => {
    Swal.fire({
      title,
      input,
      inputOptions,
      inputPlaceholder,
      showCancelButton,
      inputValidator: (value) => {
        return new Promise((resolve) => {
          if (value === "") {
            resolve("Necesitas ingresar la información");
          } else {
            resolve();
            window.livewire.emit(method, getId, value);
          }
        });
      }
    })
}

const SwalInputdate = (title, html, showCancelButton, getId, method) => {
    Swal.fire({
        title,
        html,
        showCancelButton,
        preConfirm: () => {
            const dateValue = document.getElementById('invoice-date').value;
            if (dateValue === "") {
                Swal.showValidationMessage("Necesitas seleccionar una fecha :)");
                return false;
            } else {
                window.livewire.emit(method, getId, dateValue);
            }
        }
    });
}

window.addEventListener('DOMContentLoaded', () => { 

    this.livewire.on('swal:modal', data => {
        SwalModal(data.icon, data.title, data.html, data.imageUrl, data.footer)
    })

    this.livewire.on('swal:confirm', data => {
        SwalConfirm(data.icon, data.title, data.html, data.confirmText, data.method, data.params, data.callback)
    })

    this.livewire.on('swal:confirmtext', data => {
        SwalConfirmText(data.icon, data.title, data.html, data.confirmText, data.method, data.params, data.callback)
    })

    this.livewire.on('swal:alert', data => {
        SwalAlert(data.icon, data.title, data.timeout)
    })

    this.livewire.on('swal:input', data => {
        SwalInput(data.title, data.input, data.inputOptions, data.inputPlaceholder, data.showCancelButton, data.getId, data.method)
    })

    this.livewire.on('swal:inputdate', data => {
        SwalInputdate(data.title, data.html, data.showCancelButton, data.getId, data.method);
    })

})

</script>