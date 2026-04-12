<div class="grid grid-cols-1 gap-6">
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">
            Título <span class="text-red-500">*</span>
        </label>
        <input
            type="text"
            name="title"
            id="title"
            value="{{ old('title', $book->title ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Digite o título do livro"
        >
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="author" class="block text-sm font-medium text-gray-700">
            Autor <span class="text-red-500">*</span>
        </label>
        <input
            type="text"
            name="author"
            id="author"
            value="{{ old('author', $book->author ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Digite o nome do autor"
        >
        @error('author')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="published_year" class="block text-sm font-medium text-gray-700">
            Ano de publicação
        </label>
        <input
            type="number"
            name="published_year"
            id="published_year"
            value="{{ old('published_year', $book->published_year ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Ex.: 2020"
            min="1000"
            max="{{ date('Y') }}"
        >
        @error('published_year')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">
            Descrição
        </label>
        <textarea
            name="description"
            id="description"
            rows="5"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Digite uma breve descrição do livro"
        >{{ old('description', $book->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>