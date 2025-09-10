document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("cadastro-form");
    const nomeInput = document.getElementById("nome");
    const emailInput = document.getElementById("email");
    const senhaInput = document.getElementById("password");
    const confirmarSenhaInput = document.getElementById("confirm-password");
    const togglePassword = document.getElementById("toggle-password");

    // --- FUNÇÕES DE VALIDAÇÃO ---

    const showError = (input, message) => {
        const errorSpan = document.getElementById(`${input.id}-error`);
        input.classList.add("error");
        errorSpan.textContent = message;
    };

    const clearError = (input) => {
        const errorSpan = document.getElementById(`${input.id}-error`);
        input.classList.remove("error");
        errorSpan.textContent = "";
    };

    const validateForm = () => {
        let isValid = true;
        // Limpa todos os erros antes de validar novamente
        [nomeInput, emailInput, senhaInput, confirmarSenhaInput].forEach(clearError);

        // 1. Validação do Nome: não pode estar vazio
        if (nomeInput.value.trim() === "") {
            showError(nomeInput, "O nome completo é obrigatório.");
            isValid = false;
        }

        // 2. Validação de formato de e-mail
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (emailInput.value.trim() === "") {
            showError(emailInput, "O e-mail é obrigatório.");
            isValid = false;
        } else if (!emailRegex.test(emailInput.value)) {
            showError(emailInput, "Por favor, insira um e-mail válido.");
            isValid = false;
        }

        // 3. Validação de senha: mínimo de 6 caracteres
        if (senhaInput.value.trim() === "") {
            showError(senhaInput, "A senha é obrigatória.");
            isValid = false;
        } else if (senhaInput.value.length < 6) {
            showError(senhaInput, "A senha deve ter no mínimo 6 caracteres.");
            isValid = false;
        }

        // 4. Validando se as senhas coincidem
        if (confirmarSenhaInput.value.trim() === "") {
            showError(confirmarSenhaInput, "A confirmação de senha é obrigatória.");
            isValid = false;
        } else if (senhaInput.value !== confirmarSenhaInput.value) {
            showError(confirmarSenhaInput, "As senhas não coincidem.");
            isValid = false;
        }
        
        return isValid;
    };

    // --- EVENT LISTENERS ---

    form.addEventListener("submit", function(event) {
        event.preventDefault();

        if (validateForm()) {
            // --- ATENÇÃO: Risco de Segurança ---
            // O código abaixo armazena a senha no navegador.
            // Isso NUNCA deve ser feito em um site real (produção).
            // O correto é enviar os dados para um servidor (backend) que fará o armazenamento seguro.
            localStorage.setItem("email", emailInput.value);
            localStorage.setItem("senha", senhaInput.value); // Apenas para demonstração

            alert("Cadastro realizado com sucesso!");
            window.location.href = "../login/login.html";
        }
    });

    // Funcionalidade para mostrar/ocultar senha
    togglePassword.addEventListener("click", function() {
        const type = senhaInput.getAttribute("type") === "password" ? "text" : "password";
        senhaInput.setAttribute("type", type);
        
        // Altera o ícone (opcional, mas melhora a UX)
        this.classList.toggle("bi-eye-slash-fill");
    });
});