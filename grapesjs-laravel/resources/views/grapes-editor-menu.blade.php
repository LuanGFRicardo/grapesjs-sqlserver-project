<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Selecionar Template</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Selecione um Template para Editar</h2>
  
    <form id="templateForm" method="GET" action="">
      <div class="row g-3 align-items-center">
        <div class="col-auto">
          <select id="templateSelect" class="form-select" name="template">
            <option value="">-- Escolha um template --</option>
            @foreach ($templates as $template)
              <option value="{{ $template->nome }}">{{ $template->nome }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Editar</button>
        </div>
      </div>
    </form>
  
    <hr class="my-4">
  
    <h4>Criar Novo Template</h4>
    <form id="createTemplateForm">
      <div class="row g-3 align-items-center">
        <div class="col-auto">
          <input type="text" id="novoTemplateNome" class="form-control" placeholder="Nome do novo template" required>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-success">Criar Template</button>
        </div>
      </div>
    </form>

    <div id="historico-container" class="mt-3" style="display: none;">
      <h5>Histórico de Modificações</h5>
      <ul id="historico-lista" class="list-group"></ul>
    </div>

    <hr class="my-4">
    <a class="btn btn-success" href="{{ url('componentes') }}">Gerenciar Componentes</a>
  </div>
  
  <script>
    const URL_BASE = "{{ env('URL_BASE', url('/')) }}";
    if (URL_BASE.endsWith('/')) URL_BASE = URL_BASE.slice(0, -1);

    // Redirecionar para o editor ao selecionar template
    document.getElementById('templateForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const nome = document.getElementById('templateSelect').value;
      if (!nome) return alert("Selecione um template!");
      window.location.href = `${URL_BASE}/editor/${encodeURIComponent(nome)}`;
    });
  
    // Criar novo template
    document.getElementById('createTemplateForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const nome = document.getElementById('novoTemplateNome').value.trim();
  
      if (!nome) return alert("Digite um nome para o novo template!");
  
      try {
        const res = await fetch(`${URL_BASE}/api/criar-template`, {
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
        const res = await fetch(`${URL_BASE}/api/template-historico/${encodeURIComponent(nome)}`);
        const dados = await res.json();

        if (!Array.isArray(dados)) throw new Error("Formato inválido");

        const nomeVersao = encodeURIComponent(nome);

        dados.forEach(v => {
          const li = document.createElement('li');
          li.className = 'list-group-item d-flex justify-content-between align-items-center';

          const span = document.createElement('span');
          span.textContent = new Date(v.data_criacao).toLocaleString();

          const btn = document.createElement('button');
          btn.className = 'btn btn-sm btn-outline-secondary ms-2';
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
        const res = await fetch(`${URL_BASE}/api/template-versao/${versaoId}`);
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
</body>
<p>
</html>
