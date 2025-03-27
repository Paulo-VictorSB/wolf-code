# API de Gerenciamento de Tarefas (Task Manager)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

Esta é uma API RESTful para um sistema de gerenciamento de tarefas. A API permite criar, editar, listar e excluir tarefas, e todas as ações são realizadas em conformidade com as regras de negócios associadas a cada status de tarefa.

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

### Vamos para as minhas decisões.

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
Então eu pensei, agora eu preciso criar as rotas da minha API:
```bash
    Route::middleware('api')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('get_task');
    Route::post('/tasks', [TaskController::class, 'store'])->name('new_task');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('update_task');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('delete_task');
});
```
Tive um pouco de problema nessa parte, não para implementar as rotas em si, mas sim, o conflito que estava dando com o arquivo web.php, resolvi indo na pasta bootstrap/app.php e definindo o seguinte:
```bash
    return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
        apiPrefix: ''
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

Após isso, já era a hora de criar os models e os controllers:
```bash
    // controller
    <?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{

    public function index()
    {   
        $tasks = Task::all();
        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        if (empty($data['status'])) {
            $data['status'] = 'pendente';
        }
        $tasks = Task::create($data);
        return new TaskResource($tasks);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->validated());
        return new TaskResource($task->fresh());
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'task deleted'
        ]);
    }
}

    // model

    <?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'description',
        'status',
        'due_date',
    ];
}

```

Para que tudo ocorresse bem, eu precisei também criar os requests e resources:
```bash
    <?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskRequest extends FormRequest
{

    public function authorize(): bool
    {   
        $this->validateTaskStatus();
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:pendente,em andamento,concluído',
            'due_date' => 'nullable|date'
        ];

        if ($this->isMethod('put')) {
            $task = Task::findOrFail($this->route('id'));

            if ($task->status === 'concluído') {
                return [];
            }

            $rules['title'] = 'sometimes|string|max:255';
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Error adding task, please check the fields.',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    protected function validateTaskStatus()
    {   
        if($this->isMethod('post'))
        {
            return;
        }

        $task = Task::findOrFail($this->route('id'));

        if($task->status !== 'pendente')
        {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => $this->getActionMessage()
            ], 403));
        }
    }

    protected function getActionMessage()
    {
        $action = $this->isMethod('delete') ? 'delete' : 'update';
        return "It is not possible to {$action} tasks that are not pending.";    }
}

```

### Agora vou explicar o que cada método faz.
