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

        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;

        dateElement.textContent = formattedDate;
        timeElement.textContent = formattedTime;
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
});

// temporizador

