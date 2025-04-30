<script>
    // Redirecionar para o editor ao selecionar template
    document.getElementById('templateForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const nome = document.getElementById('templateSelect').value;
      if (!nome) return alert("Selecione um template!");
      window.location.href = `${URL_BASE}/editor/${encodeURIComponent(nome)}`;
    });
</script>