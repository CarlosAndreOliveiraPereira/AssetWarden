document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("login-form"); // ID corrigido para "login-form"
    const feedbackMessage = document.getElementById("login-error"); // ID corrigido para "login-error"

    form.addEventListener("submit", async function(event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        // Coleta todos os dados do formulário
        const formData = new FormData(form);
        const loginData = Object.fromEntries(formData.entries());

        // Esconde mensagens antigas
        feedbackMessage.style.display = 'none';
        feedbackMessage.textContent = '';
        
        try {
            // --- CORREÇÃO APLICADA AQUI ---
            // A URL foi ajustada para o caminho relativo correto.
            const response = await fetch('../api/login_usuario.php', { // Assumindo que o endpoint de login é 'login_usuario.php'
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(loginData),
            });

            const result = await response.json();

            if (response.ok) {
                // Sucesso no login, redirecionar para a página principal
                window.location.href = 'Inve.html'; 
            } else { 
                feedbackMessage.textContent = result.message;
                feedbackMessage.style.display = 'block';
            }

        } catch (error) {
            console.error('Falha na comunicação com o servidor:', error);
            feedbackMessage.textContent = 'Não foi possível conectar ao servidor. Tente novamente.';
            feedbackMessage.style.display = 'block';
        }
    });
});