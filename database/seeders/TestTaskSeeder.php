<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'admin@admin.cl')->first();
        $max = 1000;
        DB::beginTransaction();
        for ($i = 1; $i <= $max; $i++) {
            $this->command->info('Creating test task ' . $i . ' of ' . $max);
            $task = new Task();
            $task->status = 'pending';
            $task->uuid = Str::uuid()->toString();
            $task->attempts = 0;
            $task->channel = 1;
            $task->percent_complete = 0;
            $task->name = 'Test task ' . $i;
            $task->payload = '';
            $task->exception = '';
            $task->user_id = $user->id;
            $task->type = 'tiles';

            // Generar una fecha aleatoria dentro del rango de los últimos 60 días
            $startDate = Carbon::now()->subDays(60)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $randomDate = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
            $task->created_at = $randomDate;

            $task->save();
        }
        DB::commit();
    }
}
