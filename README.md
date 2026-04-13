# GestaoLivros

Sistema desenvolvido em **Laravel 10**, **PHP 8** e **MySQL** para gerenciamento de livros e empréstimos, com autenticação de usuários, controle de permissões e alerta de vencimento por e-mail.

---

## Objetivo do sistema

O **GestaoLivros** tem como objetivo gerenciar o fluxo de empréstimos de livros, permitindo o cadastro e controle de usuários, livros e empréstimos, além de monitorar vencimentos e enviar notificações automáticas por e-mail para empréstimos próximos da data limite.

---

## Tecnologias utilizadas

- PHP 8.x
- Laravel 10
- MySQL
- Composer
- Node.js
- NPM
- Vite

---

## Requisitos mínimos

Antes de executar o projeto, certifique-se de ter instalado em sua máquina:

- PHP 8.x
- Composer
- Node.js e NPM
- MySQL
- Git (opcional, mas recomendado)

---

## Instalação do projeto

Clone o repositório e entre na pasta do projeto:

```bash
git clone <URL_DO_REPOSITORIO>
cd GestaoLivros
```

Instale as dependências do backend:

```bash
composer install
```

Instale as dependências do frontend:

```bash
npm install
```

Copie o arquivo de ambiente:

```bash
cp .env.example .env
```

No Windows, caso o comando acima não funcione, utilize:

```bash
copy .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

---

## Configuração do ambiente

Edite o arquivo `.env` e configure as credenciais do banco de dados:

```env
APP_NAME=GestaoLivros
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestaolivros
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

---

## Banco de dados

Crie manualmente um banco de dados no MySQL com o mesmo nome definido no `.env`.

Depois execute as migrations:

```bash
php artisan migrate
```

Caso o projeto possua seeders, execute:

```bash
php artisan db:seed
```

Ou, se desejar rodar migrations e seeders juntos:

```bash
php artisan migrate --seed
```

---

## Executando o projeto

Inicie o servidor local do Laravel:

```bash
php artisan serve
```

Em outro terminal, execute os assets do frontend:

```bash
npm run dev
```

A aplicação ficará disponível em:

```bash
http://127.0.0.1:8000
```

---

## Autenticação

O sistema possui autenticação de usuários para controle de acesso às funcionalidades da aplicação.

Dependendo da implementação do projeto, o acesso pode ser realizado por usuários previamente cadastrados no banco ou criados pelo fluxo de registro/autenticação disponível na interface.

---

## Filas

O projeto utiliza **fila** para processar o envio de e-mails de alerta de vencimento, evitando impacto direto na resposta da aplicação.

Para executar o worker da fila:

```bash
php artisan queue:work
```

Se estiver utilizando o driver `database`, certifique-se de preparar a estrutura da fila conforme documentado na seção de teste do alerta por e-mail.

---

## Scheduler

O sistema pode utilizar tarefas agendadas para execução automática de rotinas.

Para rodar o agendador localmente:

```bash
php artisan schedule:work
```

Em ambiente de servidor, o ideal é configurar um cron para executar:

```bash
php artisan schedule:run
```

Exemplo de configuração no cron:

```bash
* * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1
```

---

## Teste do alerta de vencimento por e-mail

O sistema possui um comando que verifica empréstimos com **12 horas ou menos para vencer** e envia um alerta por e-mail usando fila.

### Configuração

No arquivo `.env`, utilize:

```env
MAIL_MAILER=log
QUEUE_CONNECTION=database
```

### Preparar a fila

Execute:

```bash
php artisan queue:table
php artisan migrate
```

### Rodar o worker da fila

Execute em um terminal separado:

```bash
php artisan queue:work
```

### Executar a verificação manualmente

Execute:

```bash
php artisan loans:check-due-soon
```

### Como simular um empréstimo próximo do vencimento

Ajuste manualmente o campo `due_date` de um empréstimo ativo no banco para uma data com menos de 12 horas de diferença em relação ao horário atual.

Exemplo SQL:

```sql
UPDATE loans
SET due_date = DATE_ADD(NOW(), INTERVAL 6 HOUR),
    due_soon_notified_at = NULL
WHERE id = 1;
```

### Verificar o envio

Como o projeto utiliza `MAIL_MAILER=log`, o conteúdo do e-mail será registrado em:

```bash
storage/logs/laravel.log
```

---

## Como testar o fluxo principal do sistema

Uma sugestão de fluxo para validação manual da aplicação:

1. Acesse o sistema com um usuário autenticado.
2. Cadastre ou verifique a existência de livros no banco.
3. Realize um empréstimo para um usuário.
4. Consulte a listagem de empréstimos.
5. Ajuste manualmente a data de vencimento de um empréstimo para menos de 12 horas.
6. Execute o comando de verificação:

```bash
php artisan loans:check-due-soon
```

7. Com o worker da fila em execução, verifique o log:

```bash
storage/logs/laravel.log
```

---

## Decisões arquiteturais principais

### Actions

A lógica de negócio foi separada em **Actions**, o que ajuda a manter os controllers mais enxutos e focados apenas na camada HTTP. Essa abordagem facilita manutenção, reutilização de código e legibilidade.

### Form Requests

As validações de entrada foram centralizadas em **Form Requests**, deixando os controllers mais limpos e organizando as regras de validação em classes específicas.

### Policies

O controle de autorização foi implementado com **Policies**, garantindo que cada usuário possa executar apenas as ações permitidas dentro do sistema.

### Fila para e-mail

O envio de e-mails foi implementado com **fila**, melhorando a performance da aplicação e evitando que o usuário aguarde o processamento do disparo durante a requisição.

---

## Estrutura geral do projeto

O projeto foi organizado buscando separação de responsabilidades e facilidade de manutenção, com foco em:

- Controllers enxutos
- Regras de validação desacopladas
- Lógica de negócio isolada
- Autorização centralizada
- Processamento assíncrono para envio de e-mail

---

## Observações

Este projeto foi desenvolvido com foco em:

- boas práticas do Laravel
- organização do código
- separação de responsabilidades
- legibilidade
- manutenção futura
- clareza na execução local do ambiente

---

## Comandos úteis

Instalar dependências do backend:

```bash
composer install
```

Instalar dependências do frontend:

```bash
npm install
```

Gerar chave da aplicação:

```bash
php artisan key:generate
```

Executar migrations:

```bash
php artisan migrate
```

Executar seeders:

```bash
php artisan db:seed
```

Iniciar servidor Laravel:

```bash
php artisan serve
```

Rodar frontend:

```bash
npm run dev
```

Rodar worker da fila:

```bash
php artisan queue:work
```

Rodar scheduler local:

```bash
php artisan schedule:work
```

Executar verificação manual de vencimento:

```bash
php artisan loans:check-due-soon
```
---

## Testes automatizados

O projeto possui testes automatizados para regras principais do sistema, incluindo:

- listagem e visualização de livros
- criação de livros por usuário autenticado
- restrições de edição e remoção de livros de outros usuários
- regras de empréstimo
- devolução de livros
- comando de alerta de vencimento

### Como rodar os testes

```bash
php artisan test