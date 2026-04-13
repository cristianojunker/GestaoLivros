<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Book') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-gray-900">Editar livro</h1>
                <p class="text-sm text-gray-600">Atualize os dados do livro selecionado.</p>
            </div>

            @if ($errors->has('error'))
                <div class="mb-4 rounded-md bg-red-100 border border-red-200 px-4 py-3 text-red-700">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('books.update', $book->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('books.partials.form', ['book' => $book])

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Atualizar
                            </button>

                            <a href="{{ route('books.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>