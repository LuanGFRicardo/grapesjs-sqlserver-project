<script>
    let URL_BASE = "{{ env('URL_BASE', url('/')) }}";
    if (URL_BASE.endsWith('/')) URL_BASE = URL_BASE.slice(0, -1);

    const templateId = document.querySelector('meta[name="template-id"]')?.content;
    
    const metaTemplate = document.querySelector('meta[name="template-name"]');
    const nomeTemplate = metaTemplate ? metaTemplate.getAttribute('content') : null;

    if (!nomeTemplate) {
      alert('❌ Template não informado!');
    }

    const API = {
      salvar: `${URL_BASE}/api/salvar-template`,
      carregar: `${URL_BASE}/api/get-template/${nomeTemplate}`,
      baixar: `${URL_BASE}/api/baixar-template`,
      dados: tipo => `${URL_BASE}/api/dados/${tipo}`
    }
</script>