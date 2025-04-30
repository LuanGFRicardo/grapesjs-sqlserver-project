<script>
    const getCleanHtml = () => {
        // Limpa os conteúdos dinâmicos antes de salvar
        const wrapper = editor.getWrapper();
        const sqlContainers = wrapper.find('[data-func^="sql:"]');
        
        sqlContainers.forEach(c => {
            const inner = c.components().at(0);
            if (inner) inner.components('<p>Carregando...</p>');
        });

        return editor.getHtml();
    };

    // Indenta o HTML
    function indentarHtml(html) {
        const beautifiedHtml = window.html_beautify(html, {
            indent_size: 2,
            wrap_line_length: 120,
            preserve_newlines: true
        });

        return beautifiedHtml;
    }

    // Indenta o CSS
    function indentarCss(css) {
            const beautifiedCss = window.css_beautify(css, {
            indent_size: 2,
            preserve_newlines: true
        });
        
        return beautifiedCss;
    }
</script>