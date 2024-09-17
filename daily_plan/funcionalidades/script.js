// Reloj
document.addEventListener("DOMContentLoaded", function() {
    function updateDateTime() {
        const dateElement = document.getElementById("fecha-actual");
        const timeElement = document.getElementById("hora-actual");
        const now = new Date();

        const dateOptions = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        const formattedDate = now.toLocaleDateString('es-ES', dateOptions);

        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const formattedTime = `${hours}:${minutes}:${seconds}`;

        dateElement.textContent = formattedDate;
        timeElement.textContent = formattedTime;
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
});


// Alertas Toastr
window.toastrOptions = {
    closeButton: true,
    debug: false,
    newestOnTop: false,
    progressBar: true,
    positionClass: "toast-bottom-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "500",
    timeOut: "3000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
  };

    if (window.toastrOptions) {
        toastr.options = window.toastrOptions;
        toastr.success("Sesión iniciada correctamente.");
    }


