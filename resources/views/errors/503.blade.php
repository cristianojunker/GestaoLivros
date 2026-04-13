<x-guest-layout>
    <div class="max-w-2xl mx-auto py-16 px-6 text-center">
        <div class="text-6xl font-bold text-gray-700">503</div>
        <h1 class="mt-4 text-2xl font-semibold text-gray-900">Serviço temporariamente indisponível</h1>
        <p class="mt-3 text-gray-600">
            O sistema está temporariamente indisponível. Tente novamente mais tarde.
        </p>

        <div class="mt-8 flex items-center justify-center gap-4">
            <a href="{{ route('books.index') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Ir para livros
            </a>
        </div>
    </div>
</x-guest-layout>