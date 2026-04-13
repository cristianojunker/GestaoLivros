<x-guest-layout>
    <div class="max-w-2xl mx-auto py-16 px-6 text-center">
        <div class="text-6xl font-bold text-yellow-600">419</div>
        <h1 class="mt-4 text-2xl font-semibold text-gray-900">Sessão expirada</h1>
        <p class="mt-3 text-gray-600">
            Sua sessão expirou. Atualize a página e tente novamente.
        </p>

        <div class="mt-8 flex items-center justify-center gap-4">
            <a href="{{ url()->current() }}"
               onclick="event.preventDefault(); window.location.reload();"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Atualizar página
            </a>

            <a href="{{ route('login') }}"
               class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">
                Ir para login
            </a>
        </div>
    </div>
</x-guest-layout>