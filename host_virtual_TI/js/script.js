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


        // temporizador

        let registrarInactividad = function() {
            var temporizador;
            window.onload = resetTimer;
            // DOM Events
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            document.onmousedown = resetTimer;
            document.ontouchstart = resetTimer;
            document.onclick = resetTimer;
            document.onscroll = resetTimer;

            

            function resetTimer() {
                clearTimeout(temporizador);
                temporizador = setTimeout(cerrarSesion, 1000); // 1000 milisegundos = 1 segundo
            }
            console.log(temporizador);
        }

        registrarInactividad();