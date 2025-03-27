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
