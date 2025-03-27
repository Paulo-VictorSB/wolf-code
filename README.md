# API de Gerenciamento de Tarefas (Task Manager)

Esta é uma API RESTful para um sistema de gerenciamento de tarefas. A API permite criar, editar, listar e excluir tarefas, e todas as ações são realizadas em conformidade com as regras de negócios associadas a cada status de tarefa.

## Índice

- [Visão Geral](#visão-geral)
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Endpoints da API](#endpoints-da-api)
  - [GET /tasks](#get-task)
  - [POST /tasks](#post-task)
  - [PUT /tasks/{id}](#put-task-id)
  - [DELETE /tasks/{id}](#delete-task-id)
- [Regras de Negócio](#regras-de-negócio)
- [Validações e Erros](#validações-e-erros)
- [Exemplo de Resposta](#exemplo-de-resposta)
- [Autenticação](#autenticação)
- [Seed de Dados](#seed-de-dados)

## Visão Geral

A API de gerenciamento de tarefas permite a criação, leitura, atualização e exclusão de tarefas. Cada tarefa possui um título, uma descrição (opcional), um status (pendente, em andamento, ou concluído), e uma data de vencimento (opcional). 

### Funcionalidades:
- **CRUD de Tarefas**: Criar, ler, atualizar e excluir tarefas.
- **Controle de Status**: Apenas tarefas com status "pendente" podem ser atualizadas ou excluídas.
- **Respostas padronizadas**: API Resource para padronizar as respostas em formato JSON.

## Requisitos

Antes de rodar a aplicação, certifique-se de ter os seguintes requisitos:
- PHP >= 8.2
- Composer
- Laravel >= 12
- Banco de Dados MySQL ou MariaDB

## Instalação

Nota: Antes de realizar as migrations, crie um banco de dados chamado wolfcode e no seu arquivo .env coloque o nome do banco criado.

1. **Clone o repositório**:
    ```bash
    git clone https://github.com/Paulo-VictorSB/wolf-code.git
    ```

2. **Instale as dependências**:
    ```bash
    cd wolf-code
    composer install
    ```

3. **Configure o arquivo `.env`**:
    Copie o arquivo `.env.example` para `.env` e configure as credenciais do seu banco de dados.
    ```bash
    cp .env.example .env
    ```

4. **Gere a chave do aplicativo**:
    ```bash
    php artisan key:generate
    ```

5. **Execute as migrações para criar as tabelas do banco de dados**:
    ```bash
    php artisan migrate
    ```

6. **Popule o banco de dados com dados de exemplo (opcional)**:
    ```bash
    php artisan db:seed --class=TaskSeeder
    ```

7. **Execute o servidor**:
    ```bash
    php artisan serve
    ```

Agora a API estará disponível em `http://127.0.0.1:8000`.

## Endpoints da API

### GET /tasks

**Descrição**: Retorna uma lista de todas as tarefas.

- **URL**: `/tasks`
- **Método HTTP**: `GET`
- **Resposta**:
  - **Código de Status**: `200 OK`
  - **Corpo da Resposta**:
    ```json
    [
      {
        "id": 1,
        "title": "Título da Tarefa",
        "description": "Descrição da tarefa",
        "status": "pendente",
        "due_date": "2025-04-01T00:00:00.000000Z",
        "created_at": "2025-03-01T00:00:00.000000Z",
        "updated_at": "2025-03-01T00:00:00.000000Z"
      },
      ...
    ]
    ```

### POST /tasks

**Descrição**: Cria uma nova tarefa.

- **URL**: `/tasks`
- **Método HTTP**: `POST`
- **Corpo da Requisição**:
  ```json
  {
    "title": "Título da Tarefa",
    "description": "Descrição da tarefa",
    "status": "pendente", 
    "due_date": "2025-04-01T00:00:00.000000Z"
  }

### PUT /tasks/{id}

**Descrição**: Editar uma tarefa existente.

- **URL**: `/tasks/{id}`
- **Método HTTP**: `PUT`
- **Corpo da Requisição**:
  ```json
  {
    "id": 1,
    "title": "Título da Tarefa",
    "description": "Descrição da tarefa",
    "status": "pendente",
    "due_date": "2025-04-01T00:00:00.000000Z",
    "created_at": "2025-03-01T00:00:00.000000Z",
    "updated_at": "2025-03-01T00:00:00.000000Z"
  }

### DELETE /tasks/{id}

**Descrição**: Deletar uma tarefa existente.

- **URL**: `/tasks/{id}`
- **Método HTTP**: `DELETE`
- **Corpo da Requisição**:
  ```json
  {
    "id": 1,
    "title": "Título da Tarefa",
    "description": "Descrição da tarefa",
    "status": "pendente",
    "due_date": "2025-04-01T00:00:00.000000Z",
    "created_at": "2025-03-01T00:00:00.000000Z",
    "updated_at": "2025-03-01T00:00:00.000000Z"
  }

### Vamos para as minhas decisções.

Primeiramente, eu desenhei um fluxo-grama para entender o que precisava ser feito para realizar o sistema.

Primeiro eu pensei em criar as entidades do banco de dados:
```bash
    Schema::create('tasks', function (Blueprint $table) {
        $table->id(); 
        $table->string('title'); 
        $table->text('description')->nullable(); 
        $table->enum('status', ['pendente', 'em andamento', 'concluído'])->default('pendente'); 
        $table->date('due_date')->nullable(); 
        $table->timestamps();
    });
```

Depois, eu decidi popular usando um seeder:
```bash
    $statuses = ['pendente', 'em andamento', 'concluído'];

    $faker = Faker::create();

    for ($i = 1; $i <= 1000; $i++) {
        DB::table('tasks')->insert([
            'title' => 'Tarefa ' . $i,
            'description' => $i % 2 == 0 ? $faker->paragraph() : null,
            'status' => $statuses[array_rand($statuses)],
            'due_date' => now()->addDays(rand(1, 10)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
```