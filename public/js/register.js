
window.addEventListener("DOMContentLoaded",(event)  => { //espera que html este cargada para evitar errores

document.getElementById('registroForm').addEventListener('submit', function (e) { //•	Se añade un listener al evento submit del formulario. •	La función recibe el evento e para controlar el envío.

    // valida campos vacios
    const inputs = this.querySelectorAll('input, select');
    for (let input of inputs) {
        if (!input.value.trim()) {
            alert(`Por favor completa el campo: ${input.name}`);
            input.focus();
            e.preventDefault();
            return false;
        }
    }

    const email = this.email.value;

    // valida el correo electronico 
    let expresionRegularEmail = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/g;

    if (expresionRegularEmail.test(email)==false) {
        alert("Por favor ingresa un correo válido.");
        e.preventDefault();
    }
    //validadla contraseña

    const password = this.password.value;
    let expresionRegularPassword = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/gm;

    if (expresionRegularPassword.test(password)==false) {
        alert("La contraseña no cumple. Debe contener mayúsculas, minúsculas, números");
        e.preventDefault();
    }
});
});
