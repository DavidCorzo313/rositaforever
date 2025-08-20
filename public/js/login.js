document.getElementById('loginForm').addEventListener('submit', function (e) {

    // valida usuario y contraseña que tenga unos valores 
    const email = this.email.value;
    const password = this.password.value;

    if (!email.includes('@')) {
        alert("Correo electrónico no válido.");
        e.preventDefault();
    }
 // al password toca ver como se valida mayusculas y caracteres especiales
    if (password.trim().length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres.");
        e.preventDefault();
    }
});