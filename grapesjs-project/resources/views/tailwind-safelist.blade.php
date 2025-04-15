<!DOCTYPE html>
<html>
  <body class="bg-gray-100">
    <nav class="bg-white shadow-md px-5 z-50 fixed top-0 w-full">
      <div
        class="max-w-[1400px] mx-auto w-full flex flex-col md:flex-row md:items-center justify-between py-4 gap-4"
      >
        <!-- Logo -->
        <div class="shrink-0">
          <img src="http://127.0.0.1:8000/assets/logo.png" alt="Logo" height="55" />
        </div>

        <!-- Itens do Menu -->
        <ul
          class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-0 text-sm font-medium text-gray-800 flex-1 ml-16"
        >
          <li class="relative group">
            <a
              href="#"
              class="px-4 py-4 rounded-md hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out transform hover:scale-200 shadow-sm"
              >Institucional</a
            >
            <ul
              class="absolute top-full left-0 mt-1 bg-blue-900 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transform group-hover:translate-y-0 transition-all duration-300 ease-in-out min-w-[180px] z-50"
            >
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Design</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Desenvolvimento</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Consultoria</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Consultoria</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Consultoria</a
                >
              </li>
            </ul>
          </li>

          <li class="relative group">
            <a
              href="#"
              class="px-4 py-4 rounded-md hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out transform hover:scale-200 shadow-sm"
              >Governança</a
            >
          </li>
          <li class="relative group">
            <a
              href="#"
              class="px-4 py-4 rounded-md hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out transform hover:scale-200 shadow-sm"
              >Profissionais</a
            >
          </li>

          <li class="relative group">
            <a
              href="#"
              class="px-4 py-4 rounded-md hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out transform hover:scale-200 shadow-sm"
              >Organizações Contabéis</a
            >
          </li>

          <li class="relative group">
            <a
              href="#"
              class="px-4 py-4 rounded-md hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out transform hover:scale-200 shadow-sm"
              >Fiscalização</a
            >
          </li>

          <li class="relative group">
            <a
              href="#"
              class="px-4 py-4 rounded-md hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out transform hover:scale-200 shadow-sm"
              >Educação Continuada</a
            >
          </li>

          <li class="relative group">
            <a
              href="#"
              class="px-4 py-4 rounded-md hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out transform hover:scale-200 shadow-sm"
              >Serviços</a
            >

            <!-- Dropdown -->
            <ul
              class="absolute top-full left-0 mt-1 bg-blue-900 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transform group-hover:translate-y-0 transition-all duration-300 ease-in-out min-w-[180px] z-50"
            >
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Design</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Desenvolvimento</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Consultoria</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Consultoria</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block px-4 py-3 text-white hover:bg-blue-800 text-sm"
                  >Consultoria</a
                >
              </li>
            </ul>
          </li>
          <li class="relative group">
            <a
              href="#"
              class="py-4 px-4 rounded-md hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out transform hover:scale-105 shadow-sm"
              >Contato</a
            >
          </li>
        </ul>

        <!-- Campo de Busca -->
        <div class="relative w-full md:w-[200px]">
          <input
            type="text"
            class="w-full py-2 pl-3 pr-10 border border-gray-300 rounded-md text-sm outline-none"
            placeholder="Buscar..."
          />
          <button
            class="absolute right-2 top-1/2 -translate-y-1/2 text-blue-900 text-lg"
          >
            🔍
          </button>
        </div>

        <!-- Força o Tailwind a incluir todas as classes abaixo -->
        {{-- <div class="
        p-0 p-1 p-2 p-3 p-4 p-5 p-6
        m-0 m-1 m-2 m-3 m-4 m-5 m-6
        text-xs text-sm text-base text-lg text-xl text-2xl text-3xl text-4xl
        font-light font-normal font-medium font-bold
        text-left text-center text-right
        bg-white bg-gray-100 bg-gray-200 bg-gray-300 bg-gray-400 bg-gray-500 bg-gray-600 bg-gray-700 bg-gray-800 bg-gray-900
        bg-red-100 bg-red-200 bg-red-300 bg-red-400 bg-red-500 bg-red-600 bg-red-700 bg-red-800 bg-red-900
        bg-blue-100 bg-blue-200 bg-blue-300 bg-blue-400 bg-blue-500 bg-blue-600 bg-blue-700 bg-blue-800 bg-blue-900
        text-white text-gray-100 text-gray-200 text-gray-300 text-gray-400 text-gray-500 text-gray-600 text-gray-700 text-gray-800 text-gray-900
        text-red-100 text-red-200 text-red-300 text-red-400 text-red-500 text-red-600 text-red-700 text-red-800 text-red-900
        text-blue-100 text-blue-200 text-blue-300 text-blue-400 text-blue-500 text-blue-600 text-blue-700 text-blue-800 text-blue-900
        rounded-sm rounded-md rounded-lg rounded-xl
        border border-0 border-2 border-4 border-8 border-red-500 border-gray-500 border-blue-500
        w-full w-1/2 w-1/3 w-1/4 w-1/5 w-auto
        h-full h-1/2 h-auto
        flex flex-row flex-col items-start items-center items-end justify-start justify-center justify-end
        gap-1 gap-2 gap-4 gap-6 gap-8
        hover:bg-gray-500 hover:bg-blue-500 hover:text-white hover:text-black
        focus:bg-red-500 focus:text-white
        sm:text-sm md:text-base lg:text-lg xl:text-xl 2xl:text-2xl
        hidden
        ">
      </div> --}}
