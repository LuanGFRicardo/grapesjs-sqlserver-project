<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Editor de Newsletter - GrapesJS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="template-name" content="{{ $template->nome }}">
  <meta name="template-html" content="{{ $versao->html }}">
  <meta name="template-projeto" content="{{ $versao->projeto }}">
  
  <script>
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
<body>

  <div id="gjs"></div>
  <button onclick="salvarHistorico()">💾 Salvar</button>
  <button onclick="carregarUltimaVersao()">📂 Carregar Última Versão</button>  
  <button onclick="voltarParaMenu()">⬅️ Voltar ao Menu</button>

  <!-- JS do GrapesJS -->
  @foreach ([
    'grapes.min.js', 'grapesjs-preset-webpage.min.js', 'grapesjs-plugin-forms.min.js',
    'grapesjs-custom-code.min.js', 'grapesjs-navbar.min.js', 'grapesjs-tabs.min.js',
    'grapesjs-tooltip.min.js', 'grapesjs-touch.min.js', 'grapesjs-typed.min.js',
    'grapesjs-preset-newsletter.min.js', 'grapesjs-custom-block.js'
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
        console.log('✅ Histórico salvo:', data);
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

        editor.setComponents(data.html || '');
        editor.setStyle(data.css || '');
        editor.loadProjectData(JSON.parse(data.projeto));
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
      const wrapper = editor.getWrapper();
      const sqlContainers = wrapper.find('[data-func^="sql:"]');
      sqlContainers.forEach(c => c.components('<p>Carregando dados...</p>'));
      return editor.getHtml();
    };

    window.onload = () => {
      window.editor = grapesjs.init({
        height: '100%',
        storageManager: false,
        container: '#gjs',
        fromElement: true,
        plugins: [
          'gjs-preset-newsletter', 'grapesjs-preset-webpage', 'grapesjs-plugin-forms',
          'grapesjs-custom-code', 'grapesjs-navbar', 'grapesjs-tabs',
          'grapesjs-tooltip', 'grapesjs-touch', 'grapesjs-typed', 'gjs-custom-blocks'
        ],
        pluginsOpts: {
          'grapesjs-preset-newsletter': {
            modalLabelImport: 'Paste all your code here below and click import',
            modalLabelExport: 'Copy the code and use it wherever you want',
            importPlaceholder: '<table class="table"><tr><td class="cell">Hello world!</td></tr></table>',
            cellStyle: {
              'font-size': '12px',
              'font-weight': 300,
              'vertical-align': 'top',
              color: 'rgb(111, 119, 125)',
              margin: 0,
              padding: 0,
            }
          },
          inlineCss: true,
          codeViewerTheme: 'material'                
        },
      });

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

              let html = "<ul>";
              data.forEach(item => html += `<li>${item.Num_Registro}</li>`);
              html += "</ul>";

              container.components(html);
            })
            .catch(err => console.error(`❌ Erro no tipo [${tipo}]:`, err));
        });
      };

      editor.on('load', carregarDadosDebounced);
      editor.on('component:add', component => {
        const func = component.getAttributes()['data-func'];
        if (func?.startsWith('sql:')) carregarDadosDebounced();
      });

      editor.DomComponents.addType('sql-componente', {
        model: {
          defaults: {
            tagName: 'div',
            droppable: true,
            editable: false,
            attributes: { class: 'sql-bloco' },
          },
          init() {
            this.on('change', () => {
              const attr = this.getAttributes()['data-func'];
              if (attr?.startsWith('sql:')) carregarDadosDebounced();
            });
          },
          afterInit() {
            const attr = this.getAttributes()['data-func'];
            if (attr?.startsWith('sql:')) carregarDadosDebounced();
          }
        }
      });

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
        // Se não veio versão via meta, carrega a última versão via API
        carregarUltimaVersao();
      }
    };
  </script>
</body>
</html>
