// Visualizar.js
document.addEventListener("DOMContentLoaded", () => {
    const tabelaCorpo = document.querySelector("#tabelaMaquinas tbody");
    const pesquisaInput = document.getElementById("pesquisa");
    const semDadosDiv = document.getElementById("semDados");
    let maquinas = [];

    // Função para mostrar os dados na tabela
    function mostrarMaquinas(dados) {
        tabelaCorpo.innerHTML = "";
        semDadosDiv.style.display = dados.length === 0 ? "block" : "none";

        dados.forEach(maquina => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td data-label="Localidade">${maquina.localidade}</td>
                <td data-label="Dispositivo">${maquina.dispositivo}</td>
                <td data-label="Série">${maquina.serie}</td>
                <td data-label="Nota Fiscal">${maquina.nota_fiscal}</td>
                <td data-label="Responsável">${maquina.responsavel}</td>
                <td data-label="E-mail"><a href="mailto:${maquina.email}">${maquina.email}</a></td>
                <td data-label="Setor">${maquina.setor}</td>
                <td data-label="Win Update">${maquina.win_update}</td>
                <td data-label="SO">${maquina.sistema_operacional}</td>
            `;
            tabelaCorpo.appendChild(tr);
        });
    }

    // Função para filtrar conforme a pesquisa
    function filtrarMaquinas() {
        const termo = pesquisaInput.value.toLowerCase();
        const filtradas = maquinas.filter(maquina =>
            Object.values(maquina).some(valor =>
                String(valor).toLowerCase().includes(termo)
            )
        );
        mostrarMaquinas(filtradas);
    }

    // --- CORREÇÃO APLICADA AQUI ---
    // O caminho foi ajustado para a URL relativa correta do script PHP.
    fetch("../api/listar_maquinas.php")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            maquinas = data;
            mostrarMaquinas(maquinas);
        })
        .catch(err => {
            console.error("Erro ao carregar dados:", err);
            semDadosDiv.textContent = "Erro ao carregar os dados do servidor.";
            semDadosDiv.style.display = "block";
        });

    // Evento de input para pesquisa
    pesquisaInput.addEventListener("input", filtrarMaquinas);
});

document.getElementById('logout-btn').addEventListener('click', async () => {
    try {
        const response = await fetch('../api/logout.php', {
            method: 'POST',
        });
        const result = await response.json();
        if (response.ok) {
            window.location.href = result.redirect;
        } else {
            alert('Erro ao fazer logout.');
        }
    } catch (error) {
        console.error('Falha na comunicação com o servidor:', error);
        alert('Não foi possível se conectar ao servidor.');
    }
});