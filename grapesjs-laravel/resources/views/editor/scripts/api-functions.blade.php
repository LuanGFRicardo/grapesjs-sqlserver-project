<script>
    function handleApiError(contexto, err) {
        console.error(`❌ Erro em ${contexto}:`, err);
        alert(`❌ Erro ao ${contexto.toLowerCase()}.`);
    }

    const carregarDados = () => {
        const wrapper = editor.getWrapper();
        const sqlContainers = wrapper.find('[data-func^="sql:"]');

        sqlContainers.forEach(container => {
          const funcValue = container.getAttributes()['data-func'];
          const [, tipo] = funcValue.split(':');

          fetch(API.dados(tipo), {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
          })
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
            .catch(err => handleApiError("Carregar dados do template", err));
        });
      };

      // Debounce para evitar múltiplas chamadas rápidas
      let carregarTimeout = null;
      const carregarDadosDebounced = () => {
        clearTimeout(carregarTimeout);
        carregarTimeout = setTimeout(carregarDados, 100);
      };

    const salvarHistorico = () => {
        const htmlLimpo = getCleanHtml();
        const css = editor.getCss();

        fetch(API.salvar, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nome: nomeTemplate,
                html: indentarHtml(htmlLimpo),
                css: indentarCss(css),
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
        .catch(err => { handleApiError("Salvar versão do template", err); });
    };

    const carregarUltimaVersao = () => {
        fetch(API.carregar)
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

            // Aplica CSS/JS ao iframe do editor
            [
                `${URL_BASE}/vendor/googleapis/css/googleapiscss.css`,
                `${URL_BASE}/vendor/tailwindcss/css/tailwind-build.css`,
                `${URL_BASE}/vendor/tailwindcss/css/tailwind.min.css`,
                `${URL_BASE}/vendor/codemirror/js/codemirror.min.js`,
                `${URL_BASE}/vendor/codemirror/js/htmlmixed.min.js`,
                `${URL_BASE}/vendor/codemirror/css/codemirror.min.css`
            ].forEach(href => {
                const link = doc.createElement('link');
                link.rel = 'stylesheet';
                link.href = href;
                head.appendChild(link);
            });
        })
        .catch(err => { handleApiError("Carregar última versão", err) });
    };

    const baixarTemplate = () => {
        fetch(API.baixar, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
             },
            body: JSON.stringify({ 
                template_id: templateId
            })
        })
        .then(async res => {
            if (!res.ok) {
                const erro = await res.text();
                throw new Error(erro);
            }
            return res.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `template_{{ Str::slug($templateModel->nome, '_') }}.zip`;  // Gera ZIP do template e CSS pelo ID
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        })
        .catch(err => { handleApiError("Baixar template", err) });
    }
</script>
