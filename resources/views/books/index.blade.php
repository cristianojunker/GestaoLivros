<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Livros') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Lista de livros</h1>
                    <p class="text-sm text-gray-600">Consulte os livros cadastrados no sistema.</p>
                </div>

                @auth
                    <a href="{{ route('books.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        Novo livro
                    </a>
                @endauth
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

            <div class="mb-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('books.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div class="md:col-span-3">
                            <label for="search" class="block text-sm font-medium text-gray-700">
                                Buscar por título
                            </label>
                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ $search ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Digite parte do título do livro"
                            >
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Buscar
                            </button>

                            <a href="{{ route('books.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($books->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Capa
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Título
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Autor
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ano
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($books as $book)
                                        <tr>
                                            <td class="px-4 py-4">
                                                @if ($book->cover_image_url)
                                                    <img
                                                        src="{{ $book->cover_image_url }}"
                                                        alt="Capa de {{ $book->title }}"
                                                        class="h-16 w-12 rounded border object-cover"
                                                    >
                                                @else
                                                    <div class="h-16 w-12 rounded border bg-gray-100 flex items-center justify-center text-[10px] text-gray-500 text-center px-1">
                                                        Sem capa
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4 font-medium text-gray-900">
                                                {{ $book->title }}
                                            </td>

                                            <td class="px-4 py-4 text-gray-700">
                                                {{ $book->author }}
                                            </td>

                                            <td class="px-4 py-4 text-gray-700">
                                                {{ $book->published_year ?? '-' }}
                                            </td>

                                            <td class="px-4 py-4">
                                                @if ($book->active_loans_count === 0)
                                                    <span class="inline-flex items-center rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                                        Disponível
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                                        Emprestado
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('books.show', $book->id) }}"
                                                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition">
                                                        Ver
                                                    </a>

                                                    @can('update', $book)
                                                        <a href="{{ route('books.edit', $book->id) }}"
                                                           class="inline-flex items-center px-3 py-2 border border-indigo-300 rounded-md text-sm text-indigo-700 hover:bg-indigo-50 transition">
                                                            Editar
                                                        </a>
                                                    @endcan

                                                    @can('delete', $book)
                                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                                              onsubmit="return confirm('Tem certeza que deseja excluir este livro?');">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                    class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm text-red-700 hover:bg-red-50 transition">
                                                                Excluir
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $books->links() }}
                        </div>
                    @else
                        <div class="rounded-md border border-dashed border-gray-300 p-8 text-center">
                            <h3 class="text-lg font-semibold text-gray-900">Nenhum livro encontrado</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Tente ajustar a busca ou cadastre um novo livro.
                            </p>

                            @auth
                                <a href="{{ route('books.create') }}"
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                    Cadastrar livro
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>