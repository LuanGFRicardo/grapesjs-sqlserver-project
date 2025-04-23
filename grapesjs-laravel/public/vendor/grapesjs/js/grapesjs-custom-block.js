grapesjs.plugins.add('gjs-custom-blocks', (editor, opts = {}) => {
    const URL_BASE = window.APP_URL_BASE || '';
    const blockManager = editor.BlockManager;

    const carregarComponentesDinamicos = async () => {
      try {
        const res = await fetch(`${URL_BASE}/api/listar-componentes`);
        if (!res.ok) throw new Error("Erro ao buscar componentes do banco.");

        const componentes = await res.json();

        componentes.forEach(comp => {
          const id = `comp-${comp.id}`;

          let content = comp.html;
          
          // Aplica o CSS
          if (comp.css && comp.css.trim()) {
            content += `<style>${comp.css}`;
          }

          blockManager.add(id, {
            label: comp.nome || 'Componentes Customizados',
            category: comp.categoria || 'Categoria sem nome',
            attributes: { class: comp.icone || 'fa fa-cube' },
            content: content
          });
        });
      } catch (err) {
        console.warn("⚠️ Falha ao carregar componentes dinâmicos: ", err);
        adicionarComponentePadrao();
      }
    };

    const adicionarComponentePadrao = () => {
      blockManager.add('custom-card', {
        label: 'Card Customizado',
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
          <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg text-lg shadow-md transition duration-300 ease-in-out">
            Clique Aqui!
          </button>
        `
      });
    }

    carregarComponentesDinamicos();
    adicionarComponentePadrao();
  });
  