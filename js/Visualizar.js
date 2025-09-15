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
                <td>${maquina.localidade}</td>
                <td>${maquina.dispositivo}</td>
                <td>${maquina.serie}</td>
                <td>${maquina.nota_fiscal}</td>
                <td>${maquina.responsavel}</td>
                <td><a href="mailto:${maquina.email}">${maquina.email}</a></td>
                <td>${maquina.setor}</td>
                <td>${maquina.win_update}</td>
                <td>${maquina.sistema_operacional}</td>
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