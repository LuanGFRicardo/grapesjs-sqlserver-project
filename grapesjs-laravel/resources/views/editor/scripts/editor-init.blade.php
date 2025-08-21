<script>
  window.onload = () => {
    window.editor = grapesjs.init({
      height: '100%',
      storageManager: false,
      container: '#gjs',
      fromElement: true,
      parser: {
        html: {
          tagComponent: ['iframe', 'style']
        }
      },
      plugins: [
        'grapesjs-preset-webpage',
        'grapesjs-plugin-forms',
        'grapesjs-navbar',
        'grapesjs-custom-code',
        'grapesjs-tooltip',
        'gjs-custom-blocks'
      ], 
      assetManager: {
        // Endpoint upload imagem
        upload: `${URL_BASE}/editor/upload-imagem`,
        uploadName: 'file',
        autoAdd: true,
        multiUpload: false,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        // Upload e adiciona imagem
        uploadFile: (e) => {
          const files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
          const formData = new FormData();
          formData.append('file', files[0]);
          
          fetch(`${URL_BASE}/editor/upload-imagem`, {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          })
          .then(res => res.json())
          .then(result => {
            if (result?.url) {
              editor.AssetManager.add({ src: result.url });
            } else {
              alert('❌ Falha ao enviar imagem.');
            }
          })
          .catch(err => {
            console.error('Erro upload imagem: ', err);
          });
        }
      }
    });

    // Botões da interface
    editor.Panels.addButton('options', {
      id: 'salvar-template',
      className: 'fa fa-save',
      command: 'salvarTemplate',
      attributes: { title: 'Salvar Template' }
    });

    editor.Panels.addButton('options', {
      id: 'carregar-template',
      className: 'fa fa-spinner',
      command: 'carregarTemplate',
      attributes: { title: 'Carregar Template' }
    });

    editor.Panels.addButton('options', {
      id: 'editar-codigo',
      className: 'fa fa-pencil-square-o',
      command: 'editarCodigoTemplate',
      attributes: { title: 'Editar Código' }
    });

    editor.Panels.addButton('options', {
      id: 'baixar-template',
      className: 'fa fa-download',
      command: 'downloadTemplate',
      attributes: { title: 'Baixar Template '}
    });

    editor.Panels.addButton('options', {
      id: 'voltar-menu',
      className: 'fa fa-arrow-left',
      command: 'voltarMenu',
      attributes: { title: 'Voltar para Menu'}
    });

    // Comandos do editor
    editor.Commands.add('salvarTemplate', {
      run(editor, sender) {
        salvarHistorico();
      }
    });

    editor.Commands.add('carregarTemplate', {
      run(editor, sender) {
        carregarUltimaVersao();
      }
    });

    editor.Commands.add('editarCodigoTemplate', {
      run(editor, sender) {
        openCodeEditor();
      }
    });

    editor.Commands.add('downloadTemplate', {
      run(editor,sender) {
        baixarTemplate();
      }
    });

    editor.Commands.add('voltarMenu', {
      run(editor, sender) {
        voltarParaMenu();
      }
    });

    editor.on('load', () => {
      const doc = editor.Canvas.getDocument();
      const head = doc.head;

      // Charset + viewport
      const metaCharset = doc.createElement('meta');
      metaCharset.setAttribute('charset', 'UTF-8');
      head.appendChild(metaCharset);

      const metaViewPort = doc.createElement('meta');
      metaViewPort.name = "viewport";
      metaViewPort.content = "width=device-width, initial-scale=1.0";
      head.appendChild(metaViewPort);

      // Título do iframe
      const title = doc.createElement('title');
      title.innerText = `Editor - ${window.NOME_TEMPLATE}`;
      head.appendChild(title);

      // Estilos
      [
        `${URL_BASE}/vendor/googleapis/css/googleapiscss.css`,
        `${URL_BASE}/vendor/tailwindcss/css/tailwind-build.css`,
        `${URL_BASE}/vendor/tailwindcss/css/tailwind.min.css`,
        `${URL_BASE}/css/filament/filament/app.css`,
        `${URL_BASE}/css/filament/forms/forms.css`,
        `${URL_BASE}/css/filament/support/support.css`
      ].forEach(href => {
        const link = doc.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;
        head.appendChild(link);
      });
    });

    // Evento para carregar dados debounce
    editor.on('load', carregarDadosDebounced);

    // Recarrega dados se componente sql for adicionado
    editor.on('component:add', component => {
      const func = component.getAttributes()['data-func'];
      if (func?.startsWith('sql:')) carregarDadosDebounced();
    });

    // Tipo customizado sql-componente
    editor.DomComponents.addType('sql-componente', {
      model: {
        defaults: {
          tagName: 'div',
          droppable: true,
          editable: false,
          attributes: { class: 'sql-bloco' },
        }
      }
    });

    // Carrega versão salva ou busca API
    const htmlVersao = document.querySelector('meta[name="template-html"]')?.getAttribute('content');
    const projetoVersao = document.querySelector('meta[name="template-projeto"]')?.getAttribute('content');

    if (htmlVersao && projetoVersao) {
      editor.setComponents(decodeURIComponent(htmlVersao));
      try {
        editor.loadProjectData(JSON.parse(decodeURIComponent(projetoVersao)));
      } catch (e) {
        console.error("❌ Erro projeto JSON:", e);
      }
    } else {
      carregarUltimaVersao();
    }
  };
</script>
