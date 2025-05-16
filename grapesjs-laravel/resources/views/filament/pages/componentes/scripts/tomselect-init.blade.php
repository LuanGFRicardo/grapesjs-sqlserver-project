<script>
if (!window._editorScriptLoaded) {
    window._editorScriptLoaded = true;
    // Tom-select
    document.addEventListener('DOMContentLoaded', async () => {
        // Lista de ícones
        const res = await fetch('{{ asset('data/icons.json') }}');
        const icons = await res.json();
        
        const selects = ['novoIcone', 'editIcone'];

        selects.forEach(selectId => {
            const select = document.getElementById(selectId);
            icons.forEach(icon => {
                const option = document.createElement('option');
                option.value = icon;
                option.innerHTML = icon;
                select.appendChild(option);
            });

            new TomSelect(`#${selectId}`, {
            render: {
                option: function(data) {
                    return `<div class="flex items-center">
                        <i class="${data.value} mr-2 w-5 text-base"></i>
                        <span>${data.value.replace('fas fa-', '').replaceAll('-', ' ')}</span>
                    </div>`;
                },
                item: function(data) {
                    return `<div class="flex items-center">
                        <i class="${data.value} mr-2 w-5 text-base"></i>
                        <span>${data.value.replace('fas fa-', '').replaceAll('-', ' ')}</span>
                    </div>`;
                }
            }
            });
        });

        // Lista de componentes
        new TomSelect('#selectComponente', {
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