<script>
    // Define URL_BASE global se indefinida
    if (typeof window.URL_BASE === 'undefined') {
        window.URL_BASE = "{{ rtrim(env('URL_BASE', url('/')), '/') }}";
    }

    // ID e NOME do template via meta tag
    window.ID_TEMPLATE = document.querySelector('meta[name="template-id"]')?.content;

    window.NOME_TEMPLATE = document.querySelector('meta[name="template-name"]')?.content;
    
    // Endpoints API
    const API = {
        salvar: `${URL_BASE}/editor/salvar-template`,
        carregar: `${URL_BASE}/editor/get-template/${window.ID_TEMPLATE}`,
        baixar: `${URL_BASE}/editor/baixar-template`,
        dados: tipo => `${URL_BASE}/dados/${tipo}`
    }
</script>
