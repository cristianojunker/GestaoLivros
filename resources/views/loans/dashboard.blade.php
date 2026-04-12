<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loans Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard de empréstimos</h1>
                <p class="text-sm text-gray-600">Acompanhe os empréstimos ativos e registre devoluções.</p>
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
                <div class="p-6 text-gray-900">
                    @if ($loans->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuário
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Livro
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data do empréstimo
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data de vencimento
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
                                    @foreach ($loans as $loan)
                                        <tr>
                                            <td class="px-4 py-4 text-gray-900">
                                                {{ $loan->user->name }}
                                            </td>

                                            <td class="px-4 py-4 text-gray-900">
                                                {{ $loan->book->title }}
                                            </td>

                                            <td class="px-4 py-4 text-gray-700">
                                                {{ $loan->loan_date->format('d/m/Y H:i') }}
                                            </td>

                                            <td class="px-4 py-4 text-gray-700">
                                                {{ $loan->due_date->format('d/m/Y H:i') }}
                                            </td>

                                            <td class="px-4 py-4">
                                                @if ($loan->isOverdue())
                                                    <span class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                                        Atrasado
                                                    </span>
                                                @elseif ($loan->isCloseToDue())
                                                    <span class="inline-flex items-center rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700">
                                                        Próximo do vencimento
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                                        Em dia
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4">
                                                <div class="flex items-center justify-end">
                                                    <form action="{{ route('loans.return', $loan->id) }}" method="POST"
                                                          onsubmit="return confirm('Tem certeza que deseja registrar a devolução deste livro?');">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                class="inline-flex items-center px-3 py-2 border border-indigo-300 rounded-md text-sm text-indigo-700 hover:bg-indigo-50 transition">
                                                            Registrar devolução
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $loans->links() }}
                        </div>
                    @else
                        <div class="rounded-md border border-dashed border-gray-300 p-8 text-center">
                            <h3 class="text-lg font-semibold text-gray-900">Nenhum empréstimo ativo</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                No momento, não existem livros emprestados.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>