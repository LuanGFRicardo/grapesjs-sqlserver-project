<form id="templateForm" method="GET" action="" class="space-y-4">
  <div class="flex items-center space-x-3">
    <div class="flex-shrink-0">
      <select 
        id="templateSelect" 
        class="form-select py-2 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
        name="template">
          <option value="">-- Escolha um template --</option>
        @foreach ($templates as $template)
          <option value="{{ $template->nome }}">{{ $template->nome }}</option>
        @endforeach
      </select>
    </div>

    <div class="flex-shrink-0">
      <button 
        type="submit" 
        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition">
          Editar
        </button>
    </div>
  </div>
</form>