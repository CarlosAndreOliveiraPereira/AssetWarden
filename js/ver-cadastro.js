window.onload = function() {
    // Recupera os dados do localStorage
    const localidade = localStorage.getItem("localidade");
    const nomeDispositivo = localStorage.getItem("nome_dispositivo");
    const numeroSerie = localStorage.getItem("numero_serie");
    const notaFiscal = localStorage.getItem("nota_fiscal");
    const responsavel = localStorage.getItem("responsavel");
    const email = localStorage.getItem("email");
    const setor = localStorage.getItem("setor");
    const windowsUpdateAtivo = localStorage.getItem("windows_update_ativo");
    const sistemaOperacional = localStorage.getItem("sistema_operacional");
    const observacao = localStorage.getItem("observacao");

    // Preenche os campos com os dados recuperados
    document.getElementById("localidade").textContent = localidade;
    document.getElementById("nome_dispositivo").textContent = nomeDispositivo;
    document.getElementById("numero_serie").textContent = numeroSerie;
    document.getElementById("nota_fiscal").textContent = notaFiscal;
    document.getElementById("responsavel").textContent = responsavel;

    // Transforma o e-mail em um link clicável
    if (email) {
        document.getElementById("email").innerHTML = `<a href="mailto:${email}">${email}</a>`;
    } else {
        document.getElementById("email").textContent = "Não informado";
    }

    document.getElementById("setor").textContent = setor;
    document.getElementById("windows_update_ativo").textContent = windowsUpdateAtivo;
    document.getElementById("sistema_operacional").textContent = sistemaOperacional;
    document.getElementById("observacao").textContent = observacao;
};
