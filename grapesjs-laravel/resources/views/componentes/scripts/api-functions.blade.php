<script>
  // Abstração para chamadas API REST
  async function api(url, method = 'GET', body = null) {
    const opts = { 
      method, 
      headers: { 'X-CSRF-TOKEN': token 

    }};
    if (body) {
      opts.headers['Content-Type'] = 'application/json';
      opts.body = JSON.stringify(body);
    }
    const res = await fetch(`${URL_BASE}/api/${url}`, opts);
    if (!res.ok) throw new Error(await res.text());
    return res.json();
  }

  const btnLoad = document.getElementById('btnLoad');
  const btnDelete = document.getElementById('btnDelete');
  const editForm = document.getElementById('editForm');
  const createForm = document.getElementById('createForm');

  // Carrega dados do componente selecionado
  if(btnLoad) {
    btnLoad.addEventListener('click', async () => {
      const id = document.getElementById('selectComponente').value;
      if (!id) return alert('Selecione um componente!');
      try {
        const comp = await api(`componentes/${id}`);
        document.getElementById('editId').value = comp.id;
        document.getElementById('editNome').value = comp.nome;
        document.getElementById('editCategoria').value = comp.categoria;
        document.getElementById('editHtml').value = comp.html;
        document.getElementById('editCss').value = comp.css;
        editForm.style.display = 'block';
      } catch (e) {
        console.error(e);
        alert('Falha ao carregar componente.');
      }
    });
  }

  // Exclui o componente selecionado
  if (btnDelete) {
    btnDelete.addEventListener('click', async () => {
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
  }

  //Envia os dados atualizados do componentes
  if (editForm) {
    editForm.addEventListener('submit', async e => {
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
  }

  // Cria novo componente
  if (createForm) {
    createForm.addEventListener('submit', async e => {
      e.preventDefault();
      const body = {
        nome: document.getElementById('novoNome').value.trim(),
        categoria: document.getElementById('novoCategoria').value.trim(),
        html: document.getElementById('novoHtml').value,
        css: document.getElementById('novoCss').value,
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
  }
</script>