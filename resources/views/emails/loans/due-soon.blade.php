<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Aviso de vencimento do empréstimo</title>
</head>
<body>
    <h2>Olá, {{ $loan->user->name }}!</h2>

    <p>
        Este é um lembrete de que o prazo de devolução do livro <strong>{{ $loan->book->title }}</strong> está próximo do vencimento.
    </p>

    <p>
        <strong>Data do empréstimo:</strong> {{ $loan->loan_date->format('d/m/Y H:i') }}
    </p>

    <p>
        <strong>Data de vencimento:</strong> {{ $loan->due_date->format('d/m/Y H:i') }}
    </p>

    <p>Por favor, realize a devolução dentro do prazo.</p>

    <p>Obrigado.</p>
</body>
</html>