<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do livro') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detalhes do livro</h1>
                    <p class="text-sm text-gray-600">Visualize as informações do livro selecionado.</p>
                </div>

                <div class="flex items-center gap-2">
                    @can('update', $book)
                        <a href="{{ route('books.edit', $book->id) }}"
                           class="inline-flex items-center px-4 py-2 border border-indigo-300 rounded-md text-sm text-indigo-700 hover:bg-indigo-50 transition">
                            Editar
                        </a>
                    @endcan

                    <a href="{{ route('books.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition">
                        Voltar
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-100 border border-green-200 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('error'))
                <div class="mb-4 rounded-md bg-red-100 border border-red-200 px-4 py-3 text-red-700">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Título</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $book->title }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Autor</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $book->author }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Ano de publicação</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $book->published_year ?? '-' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1 text-base {{ $book->active_loans_count === 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $book->active_loans_count === 0 ? 'Disponível' : 'Emprestado' }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Descrição</h3>
                        <p class="mt-1 text-base text-gray-900 whitespace-pre-line">
                            {{ $book->description ?: 'Nenhuma descrição informada.' }}
                        </p>
                    </div>

                    @auth
                        <div class="pt-4 border-t border-gray-200">
                            @if ($book->active_loans_count === 0)
                                <form action="{{ route('loans.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">

                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                        Emprestar livro
                                    </button>
                                </form>
                            @else
                                <p class="text-sm text-red-600">
                                    Este livro não está disponível para empréstimo no momento.
                                </p>
                            @endif
                        </div>
                    @endauth

                    @can('delete', $book)
                        <div class="pt-4 border-t border-gray-200">
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                  onsubmit="return confirm('Tem certeza que deseja excluir este livro?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm text-red-700 hover:bg-red-50 transition">
                                    Excluir livro
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>