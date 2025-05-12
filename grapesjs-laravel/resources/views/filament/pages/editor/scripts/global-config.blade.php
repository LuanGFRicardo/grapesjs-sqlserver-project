<script>
    if (typeof window.URL_BASE === 'undefined') {
        window.URL_BASE = "{{ rtrim(env('URL_BASE', url('/')), '/') }}";
    }

    const templateId = document.querySelector('meta[name="template-id"]')?.content;
    
    const metaTemplate = document.querySelector('meta[name="template-name"]');
    const nomeTemplate = metaTemplate ? metaTemplate.getAttribute('content') : null;

    if (!nomeTemplate) {
      alert('❌ Template não informado!');
    }

    const API = {
      salvar: `${URL_BASE}/api/editor/salvar-template`,
      carregar: `${URL_BASE}/api/editor/get-template/${nomeTemplate}`,
      baixar: `${URL_BASE}/api/editor/baixar-template`,
      dados: tipo => `${URL_BASE}/api/dados/${tipo}`
    }
</script>