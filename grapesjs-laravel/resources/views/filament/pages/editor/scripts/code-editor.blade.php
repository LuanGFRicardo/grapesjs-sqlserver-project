<script>
    // Code Mirror de edição de código
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

    function closeCodeEditor() {
      document.getElementById('code-editor-modal').style.display = 'none';
    }

    function applyCodeChanges() {
      const code = codeMirrorEditor.getValue();

      // Separar CSS e HTML novamente
      const styleMatch = code.match(/<style[^>]*>([\s\S]*?)<\/style>/);
      const html = code.replace(/<style[^>]*>[\s\S]*?<\/style>/, '').trim();
      const css = styleMatch ? styleMatch[1] : '';

      // Aplicar no editor
      editor.setComponents(html);
      editor.setStyle(css);

      closeCodeEditor();
    }
</script>