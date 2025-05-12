<script>
    if (typeof window.URL_BASE === 'undefined') {
        window.URL_BASE = "{{ rtrim(env('URL_BASE', url('/')), '/') }}";
    }
</script>