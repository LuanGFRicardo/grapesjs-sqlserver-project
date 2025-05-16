<script>
if (!window._editorScriptLoaded) {
    window._editorScriptLoaded = true;
    // Tom-select
    document.addEventListener('DOMContentLoaded', async () => {
        // Lista de Templates
        new TomSelect('#templateSelect', {
            dropdownParent: 'body',
            render: {
            option: function(data, escape) {
                return `<div class="flex items-center">
                    <span>${escape(data.text)}</span>
                </div>`;
            },
            item: function(data, escape) {
                return `<div class="flex items-center">
                    <span>${escape(data.text)}</span>
                </div>`;
            }
            }
        });
    });
}
</script>