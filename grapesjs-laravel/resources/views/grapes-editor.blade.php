<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editor GrapesJS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="template-name" content="{{ $template->nome }}">
  <meta name="template-html" content="{{ rawurlencode($versao->html) }}">
  <meta name="template-projeto" content="{{ rawurlencode($versao->projeto) }}">
  
  <script>
    // Define URL base global sem barra no final
    window.APP_URL_BASE = "{{ env('URL_BASE', url('/')) }}".replace(/\/$/, '');
  </script>

  <link href="{{ asset('vendor/grapesjs/css/grapes.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('vendor/tailwindcss/css/tailwind-build.css') }}">
  <link href="{{ asset('vendor/font-awesome/css/all.min.css') }}" rel="stylesheet" />

  <style>
    html, body { margin: 0; padding: 0; height: 100%; }
    #gjs { height: 100vh; border: 3px solid #444; }
  </style>
</head>
<body class="bg-gray-100 pt-[100px]">

  <div id="gjs"></div>
  <button onclick="salvarHistorico()">💾 Salvar</button>
  <button onclick="carregarUltimaVersao()">📂 Carregar Última Versão</button>  
  <button onclick="voltarParaMenu()">⬅️ Voltar ao Menu</button>

  <!-- GrapesJS e plugins -->
  @foreach ([
    'grapes.min.js', 
    'grapesjs-preset-webpage.min.js',
    'grapesjs-preset-newsletter.js', 
    'grapesjs-plugin-forms.min.js',
    'grapesjs-custom-code.min.js',
    'grapesjs-navbar.min.js',
    'grapesjs-tooltip.min.js',
    'grapesjs-custom-block.js'
  ] as $script)
    <script src="{{ asset("vendor/grapesjs/js/$script") }}"></script>
  @endforeach

  <script>
    let URL_BASE = "{{ env('URL_BASE', url('/')) }}";
    if (URL_BASE.endsWith('/')) URL_BASE = URL_BASE.slice(0, -1);

    const metaTemplate = document.querySelector('meta[name="template-name"]');
    const nomeTemplate = metaTemplate ? metaTemplate.getAttribute('content') : null;

    if (!nomeTemplate) {
      alert('❌ Template não informado!');
    }

    const salvarHistorico = () => {
      const htmlLimpo = getCleanHtml();

      fetch(`${URL_BASE}/api/salvar-template`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          nome: nomeTemplate,
          html: htmlLimpo,
          projeto: JSON.stringify(editor.getProjectData())
        })
      })
      .then(async res => {
        if (!res.ok) {
          const erroTexto = await res.text();
          throw new Error(`Erro HTTP ${res.status}: ${erroTexto.slice(0, 500)}`);
        }
        return res.json();
      })
      .then(data => {
        alert('✅ Versão salva com sucesso!');
      })
      .catch(err => {
        console.error("❌ Erro ao salvar histórico:", err);
        alert('❌ Erro ao salvar versão do template.');
      });
    };

    const carregarUltimaVersao = () => {
      fetch(`${URL_BASE}/api/get-template/${nomeTemplate}`)
      .then(async res => {
        if (!res.ok) {
          const html = await res.text();
          throw new Error(`Resposta inesperada: ${html.slice(0, 200)}`);
        }
        return res.json();
      })
      .then(data => {
        if (!data.projeto) {
          throw new Error("Campo 'projeto' não recebido ou vazio.");
        }

        editor.loadProjectData(JSON.parse(data.projeto));

        const doc = editor.Canvas.getDocument();
        const head = doc.head;

        // Aplica estilos ao iframe do editor
        [
          `${URL_BASE}/vendor/googleapis/css/googleapiscss.css`,
          `${URL_BASE}/vendor/tailwindcss/css/tailwind-build.css`,
          `${URL_BASE}/vendor/tailwindcss/css/base.css`,
          `${URL_BASE}/vendor/tailwindcss/css/components.css`,
          `${URL_BASE}/vendor/tailwindcss/css/tailwind.min.css`,
          `${URL_BASE}/vendor/tailwindcss/css/utilities.css`
        ].forEach(href => {
          const link = doc.createElement('link');
          link.rel = 'stylesheet';
          link.href = href;
          head.appendChild(link);
        });
      })
      .catch(err => {
        console.error("❌ Erro ao carregar histórico:", err);
        alert("❌ Falha ao carregar a versão mais recente.");
      });
    };

    const voltarParaMenu = () => {
      window.location.href = '/';
    };

    const getCleanHtml = () => {
      // Limpa os conteúdos dinâmicos antes de salvar
      const wrapper = editor.getWrapper();
      const sqlContainers = wrapper.find('[data-func^="sql:"]');
      
      sqlContainers.forEach(c => {
        const inner = c.components().at(0);
        if (inner) inner.components('<p>Carregando...</p>');
      });

      return editor.getHtml();
    };

    window.onload = () => {
      window.editor = grapesjs.init({
        height: '100%',
        storageManager: false,
        container: '#gjs',
        fromElement: true,
        plugins: [
          'grapesjs-preset-webpage',
          // 'grapesjs-preset-newsletter', 
          'grapesjs-plugin-forms',
          'grapesjs-navbar',
          'grapesjs-custom-code',
          'grapesjs-tooltip',
          'gjs-custom-blocks'
        ], 
        // pluginsOpts: {
        //   'grapesjs-preset-newsletter': {
        //     blocks: ['section', 'text', 'image', 'link', 'quote', 'grid-items'],
        //     styleManager: false,
        //     deviceManager: false,
        //     modal: false,
        //     panels: false,
        //     commands: false,
        //     useCustomTheme: false,
        //   }
        // },
        assetManager: {
          // Endpoint de upload de imagem
          upload: `${URL_BASE}/api/upload-imagem`,
          uploadName: 'file',
          autoAdd: true,
          multiUpload: false,
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          // Como a URL será usada após upload
          uploadFile: (e) => {
            const files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
            const formData = new FormData();
            formData.append('file', files[0]);
            
            fetch(`${URL_BASE}/api/upload-imagem`, {
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
              console.error('Erro ao fazer upload de imagem: ', err);
            });
          }
        }
      });

      // Debounce para carregamento de dados
      let carregarTimeout = null;
      const carregarDadosDebounced = () => {
        clearTimeout(carregarTimeout);
        carregarTimeout = setTimeout(carregarDados, 100);
      };

      const carregarDados = () => {
        const wrapper = editor.getWrapper();
        const sqlContainers = wrapper.find('[data-func^="sql:"]');

        sqlContainers.forEach(container => {
          const funcValue = container.getAttributes()['data-func'];
          const [, tipo] = funcValue.split(':');

          fetch(`${URL_BASE}/api/dados/${tipo}`)
            .then(r => r.json())
            .then(data => {
              if (!Array.isArray(data)) {
                console.error("❌ Dados inválidos:", data);
                return;
              }

              let html = "";
              data.forEach(item => html += `${item.Num_Registro}`);

              container.components(html);
            })
            .catch(err => console.error(`❌ Erro no tipo [${tipo}]:`, err));
        });
      };

      editor.on('load', () => {
        const doc = editor.Canvas.getDocument();
        const head = doc.head;

        // Adiciona metadados e título no iframe do editor
        const metaCharset = doc.createElement('meta');
        metaCharset.setAttribute('charset', 'UTF-8');
        head.appendChild(metaCharset);

        const metaViewPort = ViewPort= doc.createElement('meta');
        metaViewPort.name = "viewport";
        metaViewPort.content = "width=device-width, initial-scale=1.0";
        doc.head.appendChild(metaViewPort);

        const title = doc.createElement('title');
        title.innerText = `Editor ${nomeTemplate}`;
        head.appendChild(title);

        // Carrega estilos no iframe
        [
          `${URL_BASE}/vendor/googleapis/css/googleapiscss.css`,
          `${URL_BASE}/vendor/tailwindcss/css/tailwind-build.css`,
          `${URL_BASE}/vendor/tailwindcss/css/base.css`,
          `${URL_BASE}/vendor/tailwindcss/css/components.css`,
          `${URL_BASE}/vendor/tailwindcss/css/tailwind.min.css`,
          `${URL_BASE}/vendor/tailwindcss/css/utilities.css`
        ].forEach(href => {
          const link = doc.createElement('link');
          link.rel = 'stylesheet';
          link.href = href;
          head.appendChild(link);
        });
      });

      // Evento para deletar imagem
      // editor.on('asset:remove', asset => {
      //   const imageUrl = asset.get('src');

      //   fetch(`${URL_BASE}/api/deletar-imagem`, {
      //     method: 'POST',
      //     headers: {
      //       'Content-Type': 'application/json',
      //       'X-CSRF-TOKEN': '{{ csrf_token() }}'
      //     },
      //     body: JSON.stringify({ url: imageUrl })
      //   })
      //   .then(res => res.json())
      //   .then(result => {
      //     if (!result) {
      //       console.warn('⚠️ Erro ao deletar imagem: ', result.message);
      //     }
      //   })
      //   .catch(err => {
      //     console.error('❌ Erro na exclusão da imagem:', err);
      //   })
      // });

      // Evento para componentes dinâmicos
      editor.on('load', carregarDadosDebounced);

      editor.on('component:add', component => {
        const func = component.getAttributes()['data-func'];
        if (func?.startsWith('sql:')) carregarDadosDebounced();
      });

      // Tipo de componente customizado
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

      // Carrega versão salva ou busca via API
      const htmlVersao = document.querySelector('meta[name="template-html"]')?.getAttribute('content');
      const projetoVersao = document.querySelector('meta[name="template-projeto"]')?.getAttribute('content');

      if (htmlVersao && projetoVersao) {
        editor.setComponents(decodeURIComponent(htmlVersao));
        try {
          editor.loadProjectData(JSON.parse(decodeURIComponent(projetoVersao)));
        } catch (e) {
          console.error("❌ Erro ao carregar projeto JSON:", e);
        }
      } else {
        carregarUltimaVersao();
      }
    };
  </script>
</body>
</html>
