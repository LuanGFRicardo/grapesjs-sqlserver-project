grapesjs.plugins.add("gjs-custom-blocks", (editor, opts = {}) => {
    const URL_BASE = window.URL_BASE || "";
    const blockManager = editor.BlockManager;

    const carregarComponentesDinamicos = async () => {
        try {
            const res = await fetch(`${URL_BASE}/componentes`);
            if (!res.ok)
                throw new Error("Erro ao buscar componentes do banco.");

            const componentes = await res.json();

            componentes.forEach((comp) => {
                const id = `comp-${comp.id}`;

                let content = comp.html;

                // Aplica o CSS
                if (comp.css && comp.css.trim()) {
                    content += `<style>${comp.css}</style>`;
                }

                blockManager.add(id, {
                    label: `
            <i class="${comp.icone || "fa fa-cube"}"></i>
            <div>${comp.nome || "Componentes Customizados"}</div>
          `,
                    category: comp.categoria || "Categoria sem nome",
                    content: content,
                });
            });
        } catch (err) {
            console.warn("⚠️ Falha ao carregar componentes dinâmicos: ", err);
            adicionarComponentePadrao();
        }
    };

    const adicionarComponentePadrao = () => {
        blockManager.add("custom-card", {
            label: "Card Customizado",
            category: "Componentes Customizados",
            attributes: { class: "fa fa-id-card" },
            content: `
        <div class="rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden w-full max-w-md mx-auto">
          <img src="${URL_BASE}/assets/cadu-home-icon-green.png" class="w-full h-48 object-cover" alt="Imagem do componente">
          <div class="p-4 space-y-2">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white">Teste Components</h5>
            <p class="text-sm text-gray-600 dark:text-gray-300">Este é um componente customizado com visual do Filament.</p>
          </div>
        </div>
      `,
        });

        blockManager.add("botao-customizado", {
            label: "Botão Customizado",
            category: "Componentes Customizados",
            attributes: { class: "fa fa-hand-pointer" },
            content: `
        <button class="inline-flex items-center justify-center rounded-md 
                bg-green-600 text-white 
                px-6 py-2.5 text-sm font-semibold shadow-sm 
                hover:bg-green-700 
                focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
          Clique Aqui!
        </button>
      `,
        });
    };

    carregarComponentesDinamicos();
    adicionarComponentePadrao();
});
