document.addEventListener("DOMContentLoaded", () => {
  const tabelaCorpo = document.querySelector("#tabelaMaquinas tbody");
  const pesquisaInput = document.getElementById("pesquisa");
  const semDadosDiv = document.getElementById("semDados");
  let maquinas = [];

  // Função para mostrar os dados na tabela
  function mostrarMaquinas(dados) {
    tabelaCorpo.innerHTML = "";

    if (dados.length === 0) {
      semDadosDiv.style.display = "block";
      return;
    } else {
      semDadosDiv.style.display = "none";
    }

    dados.forEach(maquina => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${maquina.localidade}</td>
        <td>${maquina.dispositivo}</td>
        <td>${maquina.serie}</td>
        <td>${maquina.nota_fiscal}</td>
        <td>${maquina.responsavel}</td>
        <td>${maquina.email}</td>
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
    const filtradas = maquinas.filter(maquina => {
      return Object.values(maquina).some(valor =>
        valor.toLowerCase().includes(termo)
      );
    });
    mostrarMaquinas(filtradas);
  }

  // Busca os dados do PHP via fetch
  fetch("listar_maquinas.php")
    .then(response => response.json())
    .then(data => {
      maquinas = data;
      mostrarMaquinas(maquinas);
    })
    .catch(err => {
      console.error("Erro ao carregar dados:", err);
      semDadosDiv.textContent = "Erro ao carregar dados.";
      semDadosDiv.style.display = "block";
    });

  // Evento de input para pesquisa
  pesquisaInput.addEventListener("input", filtrarMaquinas);
});
