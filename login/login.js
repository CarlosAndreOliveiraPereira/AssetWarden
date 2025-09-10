document.getElementById("login-form").addEventListener("submit", function(event) {
    event.preventDefault();

    const emailDigitado = document.getElementById("email").value;
    const senhaDigitada = document.getElementById("password").value;

    const emailSalvo = localStorage.getItem("email");
    const senhaSalva = localStorage.getItem("senha");

    if (emailDigitado === emailSalvo && senhaDigitada === senhaSalva) {
        window.location.href = "../Inventario/Inve.html";
    } else {
        alert("E-mail ou senha incorretos!");
    }
});
