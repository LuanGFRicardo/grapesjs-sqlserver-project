<div>
    <label class="text-sm font-semibold text-gray-800 dark:text-gray-200">
        {{ $getLabel() }}
    </label>

    <select 
        id="icone-select-{{ $getStatePath() }}"
        {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
        class="filament-forms-select block w-full rounded-md border-gray-300
            dark:border-gray-700 dark:bg-gray-900 dark:text-white
            shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
            @if($getState())
                <option value="{{ $getState() }}" selected>{{ $getState() }}</option>
            @endif
    </select>
</div>

@push('styles')
    <link href="{{ asset('vendor/tom-select/css/tom-select.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tom-select/css/custom-tom-select.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/font-awesome/css/all.min.css') }}" rel="stylesheet"/>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/tom-select/js/tom-select.complete.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initTomSelectIcone();
        });

        if (typeof Livewire !== 'undefined' && typeof Livewire.hook === 'function') {
            Livewire.hook('message.processed', () => {
                initTomSelectIcone();
            });
        }

        async function initTomSelectIcone() {
            const selectId = 'icone-select-{{ $getStatePath() }}';
            const select = document.getElementById(selectId);
            if (!select) return;

            // Previna múltiplas inicializações
            if (select.dataset.tomSelectInitialized === "true") return;

            select.dataset.tomSelectInitialized = "true";

            // Limpa opções antigas exceto a primeira
            select.querySelectorAll('option:not(:first-child)').forEach(opt => opt.remove());

            try {
                const res = await fetch('/data/icons.json');
                if (!res.ok) throw new Error('Erro ao carregar ícones');
                const icons = await res.json();

                icons.forEach(icon => {
                    const option = document.createElement('option');
                    option.value = icon;
                    option.textContent = icon;
                    select.appendChild(option);
                });

                new TomSelect(select, {
                    render: {
                        placeholder: 'Selecione um ícone...',
                        option: data => `<div class="flex items-center">
                            <i class="${data.value} mr-2 w-5 text-base"></i>
                            <span>${data.value.replace('fas fa-', '').replaceAll('-', ' ')}</span>
                        </div>`,
                        item: data => `<div class="flex items-center">
                            <i class="${data.value} mr-2 w-5 text-base"></i>
                            <span>${data.value.replace('fas fa-', '').replaceAll('-', ' ')}</span>
                        </div>`
                    },
                    valueField: 'value',
                    labelField: 'text',
                    searchField: ['text'],
                    placeholder: 'Selecione um ícone...'
                });
            } catch (err) {
                console.error('Erro ao carregar icons.json:', err);
            }
        }
    </script>
@endpush
