// Inve.js
document.getElementById("cadastro-form").addEventListener("submit", async function(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    // Coleta todos os dados do formulário
    const formData = new FormData(event.target);
    const dispositivoData = Object.fromEntries(formData.entries());

    const feedbackMessage = document.getElementById('feedback-message');
    feedbackMessage.style.display = 'none'; // Esconde mensagens antigas

    // Validação simples no frontend
    if (!dispositivoData.localidade || !dispositivoData.nome_dispositivo || !dispositivoData.numero_serie) {
        feedbackMessage.textContent = 'Por favor, preencha os campos obrigatórios!';
        feedbackMessage.className = 'error';
        feedbackMessage.style.display = 'block';
        return;
    }

    try {
        // --- CORREÇÃO APLICADA AQUI ---
        // A URL foi ajustada para o caminho relativo correto.
        const response = await fetch('../api/cadastrar_dispositivo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dispositivoData),
        });

        const result = await response.json();

        if (response.ok) { // Sucesso (status 201)
            feedbackMessage.textContent = result.message;
            feedbackMessage.className = 'success';
            event.target.reset(); // Limpa o formulário
            // Opcional: redirecionar após um tempo
            setTimeout(() => {
                window.location.href = "Visualizar.html"; // Caminho corrigido
            }, 2000); // Redireciona após 2 segundos
        } else { // Erro (status 4xx, 5xx)
            feedbackMessage.textContent = `Erro: ${result.message}`;
            feedbackMessage.className = 'error';
        }

    } catch (error) {
        console.error('Falha na comunicação com o servidor:', error);
        feedbackMessage.textContent = 'Não foi possível conectar ao servidor. Tente novamente.';
        feedbackMessage.className = 'error';
    }

    feedbackMessage.style.display = 'block';
});