<script>
    const URL_BASE = "{{ env('URL_BASE', url('/')) }}";
    if (URL_BASE.endsWith('/')) URL_BASE = URL_BASE.slice(0, -1);
</script>