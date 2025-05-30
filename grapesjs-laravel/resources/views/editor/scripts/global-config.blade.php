<script>
    // Define URL_BASE global se indefinida
    if (typeof window.URL_BASE === 'undefined') {
        window.URL_BASE = "{{ rtrim(env('URL_BASE', url('/')), '/') }}";
    }

    // ID do template
    const templateId = document.querySelector('meta[name="template-id"]')?.content;
    
    // Nome do template
    const metaTemplate = document.querySelector('meta[name="template-name"]');
    const nomeTemplate = metaTemplate ? metaTemplate.getAttribute('content') : null;

    // Alerta se nome não informado
    if (!nomeTemplate) {
      alert('❌ Template não informado!');
    }

    // Endpoints API
    const API = {
      salvar: `${URL_BASE}/editor/salvar-template`,
      carregar: `${URL_BASE}/editor/get-template/${nomeTemplate}`,
      baixar: `${URL_BASE}/editor/baixar-template`,
      dados: tipo => `${URL_BASE}/dados/${tipo}`
    }
</script>
