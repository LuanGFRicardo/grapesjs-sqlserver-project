<script>
  // Criar novo template
  if (!window._createTemplateFormScriptLoaded) {
    window._createTemplateFormScriptLoaded = true;
    document.getElementById('createTemplateForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const nome = document.getElementById('novoTemplateNome').value.trim();

      if (!nome) return alert("Digite um nome para o novo template!");

      try {
        const res = await fetch(`${URL_BASE}/api/menu/criar-template`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ nome })
        });

        if (!res.ok) throw new Error("Ocorreu um erro ao criar template");

        const data = await res.json();
        window.location.href = `${URL_BASE}/editor/${encodeURIComponent(nome)}`;
      } catch (err) {
        console.error(err);
        alert('Erro ao criar template!');
      }
    });
  }

  // Busca histórico de versões ao selecionar template
  document.getElementById('templateSelect').addEventListener('change', async function() {
    const nome = this.value;
    const historicoContainer = document.getElementById('historico-container');
    const historicoLista = document.getElementById('historico-lista');

    historicoLista.innerHTML = '';
    if (!nome) {
      historicoContainer.style.display = 'none';
      return;
    }

    try {
      const res = await fetch(`${URL_BASE}/api/menu/template-historico/${encodeURIComponent(nome)}`);
      const dados = await res.json();

      if (!Array.isArray(dados)) throw new Error("Formato inválido");

      const nomeVersao = encodeURIComponent(nome);

      dados.forEach(v => {
        const li = document.createElement('li');
        li.className = 'flex justify-between items-center bg-white dark:bg-gray-800 border border-gray-300 rounded-lg px-6 py-3 shadow-md transition-all';

        const span = document.createElement('span');
        span.textContent = new Date(v.data_criacao).toLocaleString();
        span.className = 'text-sm dark:text-white';

        const btn = document.createElement('button');
        btn.className = 'bg-primary-600 text-white text-sm font-medium border border-primary-600 rounded-md px-4 py-2 transition duration-200';

        btn.textContent = 'Editar esta versão';

        btn.addEventListener('click', () => editarVersao(v.id, nome));

        li.appendChild(span);
        li.appendChild(btn);
        historicoLista.appendChild(li);
      });

      historicoContainer.style.display = 'block';
    } catch (err) {
      console.error("Erro ao buscar histórico:", err);
      historicoContainer.style.display = 'none';
    }
  });

  // Abre o editor com uma versão específica
  async function editarVersao(versaoId, nomeVersao) {
    try {
      const res = await fetch(`${URL_BASE}/api/menu/template-versao/${versaoId}`);
      if (!res.ok) throw new Error("Erro ao buscar versão");
      const data = await res.json();

      const params = new URLSearchParams({
        versao: versaoId,
        html: data.html,
      });


      window.location.href = `${URL_BASE}/editor/${encodeURIComponent(nomeVersao)}?${params.toString()}`;
    } catch (err) {
      console.error("Erro ao editar versão:", err);
      alert("Não foi possível carregar a versão.");
    }
  }
</script>