document.getElementById("cadastro-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const localidade = document.getElementById("localidade").value;
    const nomeDispositivo = document.getElementById("nome_dispositivo").value;
    const numeroSerie = document.getElementById("numero_serie").value;
    const notaFiscal = document.getElementById("nota_fiscal").value;
    const responsavel = document.getElementById("responsavel").value;
    const email = document.getElementById("email").value;
    const setor = document.getElementById("setor").value;
    const windowsUpdateAtivo = document.getElementById("windows_update_ativo").value;
    const sistemaOperacional = document.getElementById("sistema_operacional").value;
    const observacao = document.getElementById("observacao").value;

    // Validação: Verificar se todos os campos obrigatórios foram preenchidos
    if (!localidade || !nomeDispositivo || !numeroSerie || !notaFiscal || !responsavel || !email || !setor || !windowsUpdateAtivo || !sistemaOperacional || !observacao) {
        alert("Por favor, preencha todos os campos!");
        return;
    }

    // Se tudo estiver correto, redireciona para a página de sucesso
    window.location.href = "../sucesso.html";
});
