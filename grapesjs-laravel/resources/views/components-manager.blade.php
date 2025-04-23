<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Gerenciar Componentes</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Gerenciador de Componentes</h2>

    <div class="card mb-4">
      <div class="card-header">Editar Componente Existente</div>
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label for="selectComponente" class="form-label">Componente</label>
            <select id="selectComponente" class="form-select">
              <option value="">-- Selecione --</option>
              @foreach($componentes as $comp)
                <option value="{{ $comp->id }}">{{ $comp->nome }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-8 text-end">
            <button id="btnLoad" class="btn btn-primary">Carregar</button>
            <button id="btnDelete" class="btn btn-outline-danger">Excluir</button>
          </div>
        </div>

        {{-- Formulário de edição de componentes --}}
        <form id="editForm" class="mt-4" style="display:none;">
          <input type="hidden" id="editId">
          <div class="mb-3">
            <label for="editNome" class="form-label">Nome</label>
            <input type="text" id="editNome" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="editCategoria" class="form-label">Categoria</label>
            <input type="text" id="editCategoria" class="form-control">
          </div>
          <div class="mb-3">
            <label for="editHtml" class="form-label">HTML Padrão</label>
            <textarea id="editHtml" rows="4" class="form-control" required></textarea>
          </div>
          <div class="mb-3">
            <label for="editCss" class="form-label">CSS</label>
            <textarea id="editCss" rows="3" class="form-control"></textarea>
          </div>
          <button type="submit" class="btn btn-success">Salvar Alterações</button>
        </form>
      </div>
    </div>

    {{-- Formulário de criação de componentes --}}
    <div class="card mb-4">
      <div class="card-header">Adicionar Novo Componente</div>
      <div class="card-body">
        <form id="createForm">
          <div class="mb-3">
            <label for="newNome" class="form-label">Nome</label>
            <input type="text" id="newNome" class="form-control" placeholder="ex: componente_sql" required>
          </div>
          <div class="mb-3">
            <label for="newCategoria" class="form-label">Categoria</label>
            <input type="text" id="newCategoria" class="form-control" placeholder="ex: Formulários">
          </div>
          <div class="mb-3">
            <label for="newHtml" class="form-label">HTML Padrão</label>
            <textarea id="newHtml" rows="4" class="form-control" required></textarea>
          </div>
          <div class="mb-3">
            <label for="newCss" class="form-label">CSS</label>
            <textarea id="newCss" rows="3" class="form-control"></textarea>
          </div>
          <button type="submit" class="btn btn-success">Criar Componente</button>
          <button class="btn btn-success" onclick="voltarParaMenu()">⬅️ Voltar ao Menu</button>
        </form>
      </div>
    </div>

  </div>

  <script>
    let URL_BASE = "{{ url('/') }}";
    const token = document.querySelector('meta[name="csrf-token"]').content;

    // Abstração para chamadas API REST
    async function api(url, method = 'GET', body = null) {
      const opts = { method, headers: { 'X-CSRF-TOKEN': token }};
      if (body) {
        opts.headers['Content-Type'] = 'application/json';
        opts.body = JSON.stringify(body);
      }
      const res = await fetch(`${URL_BASE}/api/${url}`, opts);
      if (!res.ok) throw new Error(await res.text());
      return res.json();
    }

    // Carrega dados do componente selecionado
    document.getElementById('btnLoad').addEventListener('click', async () => {
      const id = document.getElementById('selectComponente').value;
      if (!id) return alert('Selecione um componente!');
      try {
        const comp = await api(`componentes/${id}`);
        document.getElementById('editId').value = comp.id;
        document.getElementById('editNome').value = comp.nome;
        document.getElementById('editCategoria').value = comp.categoria;
        document.getElementById('editHtml').value = comp.html;
        document.getElementById('editCss').value = comp.css;
        document.getElementById('editForm').style.display = 'block';
      } catch (e) {
        console.error(e);
        alert('Falha ao carregar componente.');
      }
    });

    // Exclui o componente selecionado
    document.getElementById('btnDelete').addEventListener('click', async () => {
      const id = document.getElementById('selectComponente').value;
      if (!id) return alert('Selecione um componente para excluir.');
      if (!confirm('Confirma exclusão deste componente?')) return;
      try {
        await api(`componentes/${id}`, 'DELETE');
        location.reload();
      } catch (e) {
        console.error(e);
        alert('Erro ao excluir.');
      }
    });

    //Envia os dados atualizados do componentes
    document.getElementById('editForm').addEventListener('submit', async e => {
      e.preventDefault();
      const id = document.getElementById('editId').value;
      const body = {
        nome: document.getElementById('editNome').value.trim(),
        categoria: document.getElementById('editCategoria').value.trim(),
        html: document.getElementById('editHtml').value,
        css: document.getElementById('editCss').value,
      };
      try {
        await api(`componentes/${id}`, 'PUT', body);
        alert('Componente atualizado com sucesso!');
        location.reload();
      } catch (e) {
        console.error(e);
        alert('Erro ao atualizar.');
      }
    });

    // Cria novo componente
    document.getElementById('createForm').addEventListener('submit', async e => {
      e.preventDefault();
      const body = {
        nome: document.getElementById('newNome').value.trim(),
        categoria: document.getElementById('newCategoria').value.trim(),
        html: document.getElementById('newHtml').value,
        css: document.getElementById('newCss').value,
      };
      try {
        await api('componentes', 'POST', body);
        alert('Componente criado!');
        location.reload();
      } catch (e) {
        console.error(e);
        alert('Erro ao criar componente.');
      }
    });

    const voltarParaMenu = () => {
      window.location.href = '/';
    };
  </script>
</body>
</html>
