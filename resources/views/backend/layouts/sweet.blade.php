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
        reverseButtons: true,
        backdrop: `
            rgba(0,0,123,0.4)
            left top
            no-repeat
          `
    }).then(result => {
        if (result.value) {
            return livewire.emit(method, ...params)
        }

        if (callback) {
            return livewire.emit(callback)
        }
    })
}

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
            resolve("Necesitas seleccionar algo :)");
          } else {
            resolve();
            window.livewire.emit(method, getId, value);
          }
        });
      }
    })
}

window.addEventListener('DOMContentLoaded', () => { 

    this.livewire.on('swal:modal', data => {
        SwalModal(data.icon, data.title, data.html, data.imageUrl, data.footer)
    })

    this.livewire.on('swal:confirm', data => {
        SwalConfirm(data.icon, data.title, data.html, data.confirmText, data.method, data.params, data.callback)
    })

    this.livewire.on('swal:alert', data => {
        SwalAlert(data.icon, data.title, data.timeout)
    })

    this.livewire.on('swal:input', data => {
        SwalInput(data.title, data.input, data.inputOptions, data.inputPlaceholder, data.showCancelButton, data.getId, data.method)
    })

})

</script>