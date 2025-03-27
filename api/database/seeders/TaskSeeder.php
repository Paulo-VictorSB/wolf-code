<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{

    public function run()
    {
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
    }
}

