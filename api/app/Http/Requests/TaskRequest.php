<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Regras básicas para criação (POST)
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'string|in:pendente,em andamento,concluído',
            'due_date' => 'nullable|date'
        ];

        // Se for atualização (PUT)
        if ($this->isMethod('put')) {
            $task = Task::findOrFail($this->route('id'));

            // Se a tarefa JÁ ESTIVER concluída
            if ($task->status === 'concluído') {
                // BLOQUEIA TODAS as atualizações
                return [];
            }

            // Se NÃO ESTIVER concluída, mantém as regras mas torna campos opcionais
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
}
