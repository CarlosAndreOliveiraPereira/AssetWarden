document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("cadastro-form");
    const nomeInput = document.getElementById("nome");
    const emailInput = document.getElementById("email");
    const senhaInput = document.getElementById("password");
    const confirmarSenhaInput = document.getElementById("confirm-password");

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
        [nomeInput, emailInput, senhaInput, confirmarSenhaInput].forEach(clearError);

        if (nomeInput.value.trim() === "") {
            showError(nomeInput, "O nome completo é obrigatório.");
            isValid = false;
        }

        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (emailInput.value.trim() === "") {
            showError(emailInput, "O e-mail é obrigatório.");
            isValid = false;
        } else if (!emailRegex.test(emailInput.value)) {
            showError(emailInput, "Por favor, insira um e-mail válido.");
            isValid = false;
        }

        if (senhaInput.value.trim() === "") {
            showError(senhaInput, "A senha é obrigatória.");
            isValid = false;
        } else if (senhaInput.value.length < 6) {
            showError(senhaInput, "A senha deve ter no mínimo 6 caracteres.");
            isValid = false;
        }

        if (confirmarSenhaInput.value.trim() === "") {
            showError(confirmarSenhaInput, "A confirmação de senha é obrigatória.");
            isValid = false;
        } else if (senhaInput.value !== confirmarSenhaInput.value) {
            showError(confirmarSenhaInput, "As senhas não coincidem.");
            isValid = false;
        }
        
        return isValid;
    };

    // --- EVENT LISTENER DO FORMULÁRIO (MODIFICADO) ---
    form.addEventListener("submit", async function(event) {
        event.preventDefault();

        if (validateForm()) {
            const dadosCadastro = {
                nome: nomeInput.value,
                email: emailInput.value,
                senha: senhaInput.value,
            };

            try {
                // --- CORREÇÃO APLICADA AQUI ---
                // A URL foi ajustada para o caminho relativo correto.
                const response = await fetch('../api/cadastrar_usuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(dadosCadastro),
                });

                const result = await response.json();

                if (response.ok) { // Se a resposta for sucesso (status 2xx)
                    alert(result.message); // "Usuário cadastrado com sucesso!"
                    window.location.href = "ver-cadastro.html"; // Redireciona para a visualização
                } else { // Se a resposta for erro (status 4xx ou 5xx)
                    // Mostra o erro em um local mais visível, como no campo de email
                    showError(emailInput, result.message);
                    alert(`Erro: ${result.message}`); // Ex: "Este e-mail já está em uso!"
                }

            } catch (error) {
                console.error('Falha na comunicação com o servidor:', error);
                alert('Não foi possível se conectar ao servidor. Verifique se o XAMPP está ligado e tente novamente mais tarde.');
            }
        }
    });
});