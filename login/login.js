document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("cadastro-form");
    const feedbackMessage = document.getElementById("feedback-message");

    form.addEventListener("submit", async function(event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        // Coleta todos os dados do formulário
        const formData = new FormData(form);
        const dispositivoData = Object.fromEntries(formData.entries());

        // Esconde mensagens antigas
        feedbackMessage.style.display = 'none';
        feedbackMessage.textContent = '';
        feedbackMessage.className = '';

        try {
            // Envia os dados para o novo endpoint no backend
            const response = await fetch('http://127.0.0.1:5000/cadastrar_dispositivo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dispositivoData),
            });

            const result = await response.json();

            if (response.ok) { // Sucesso (status 201)
                feedbackMessage.textContent = result.message;
                feedbackMessage.classList.add('success');
                form.reset(); // Limpa o formulário após o sucesso
            } else { // Erro (status 400, 409, 500)
                feedbackMessage.textContent = result.message;
                feedbackMessage.classList.add('error');
            }

        } catch (error) {
            console.error('Falha na comunicação com o servidor:', error);
            feedbackMessage.textContent = 'Não foi possível conectar ao servidor. Tente novamente.';
            feedbackMessage.classList.add('error');
        }
    });
});