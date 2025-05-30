<script>
    // Editor de código com CodeMirror
    let codeMirrorEditor;

    function openCodeEditor() {
      const html = editor.getHtml();
      const css = editor.getCss();
      const beautifiedHtml = indentarHtml(html);
      const beautifiedCss = indentarCss(css);
      const fullCode = `<style>\n${beautifiedCss}</style>\n${beautifiedHtml}`;

      const modal = document.getElementById('code-editor-modal');
      modal.style.display = 'block';

      setTimeout(() => {
        if (!codeMirrorEditor) {
          codeMirrorEditor = CodeMirror.fromTextArea(document.getElementById("code-editor"), {
            mode: "htmlmixed",
            lineNumbers: true,
            lineWrapping: true,
            theme: "dracula",
          });
        }
        codeMirrorEditor.setValue(fullCode);
        codeMirrorEditor.refresh();
      }, 10);
    }

    // Fecha o modal do editor de código
    function closeCodeEditor() {
      document.getElementById('code-editor-modal').style.display = 'none';
    }

    // Aplica mudanças do editor ao GrapesJS
    function applyCodeChanges() {
      const code = codeMirrorEditor.getValue();

      // Separa CSS e HTML
      const styleMatch = code.match(/<style[^>]*>([\s\S]*?)<\/style>/);
      const html = code.replace(/<style[^>]*>[\s\S]*?<\/style>/, '').trim();
      const css = styleMatch ? styleMatch[1] : '';

      editor.setComponents(html);
      editor.setStyle(css);

      closeCodeEditor();
    }
</script>
