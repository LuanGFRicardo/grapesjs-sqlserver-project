grapesjs.plugins.add('gjs-custom-blocks', (editor, opts = {}) => {
    const URL_BASE = window.APP_URL_BASE || '';

    const blockManager = editor.BlockManager;
  
    blockManager.add('custom-card', {
      label: 'Card Bootstrap',
      category: 'Componentes Customizados',
      attributes: { class: 'fa fa-id-card' },
      content: `
        <div class="card" style="width: 18rem;">
          <img src="${URL_BASE}/assets/cadu-home-icon-green.png" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">Teste Components</h5>
            <p class="card-text">Este é um componente customizado com Bootstrap.</p>
          </div>
        </div>
      `
    });
  
    blockManager.add('botao-customizado', {
      label: 'Botão Customizado',
      category: 'Componentes Customizados',
      attributes: { class: 'fa fa-hand-pointer' },
      content: `
        <button class="btn btn-danger btn-lg w-100">Clique Aqui!</button>
      `
    });

    editor.BlockManager.add('bloco-sql-registro', {
        label: 'SQL: Registro',
        category: 'Componentes Customizados',
        attributes: { class: 'fa fa-database' },
        content: {
            type: 'sql-componente',
            // 👇 Isso aqui é fundamental para o componente raiz ter o atributo
            attributes: { 'data-func': 'sql:registro' }, 
            content: `<div><p>Carregando dados...</p></div>`
        }
    });
     
    editor.BlockManager.add('bloco-lista-sql', {
      label: 'SQL: Lista Registros',
      category: 'Componentes Customizados',
      attributes: { class: 'fa fa-list' },
      content: {
        type: 'sql-componente',
        attributes: { 'data-func': 'sql:lista-registros' },
        content: `<div><p>Carregando dados...</p></div>`
      }
    });
  });
  