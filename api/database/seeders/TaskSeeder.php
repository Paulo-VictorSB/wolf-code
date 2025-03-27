<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['pendente', 'em andamento', 'concluído'];

        for ($i = 1; $i <= 10; $i++) {
            DB::table('tasks')->insert([
                'title' => 'Tarefa ' . $i,
                'description' => $i % 2 == 0 ? 'Descrição da tarefa ' . $i : null,
                'status' => $statuses[array_rand($statuses)],
                'due_date' => now()->addDays(rand(1, 10)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
