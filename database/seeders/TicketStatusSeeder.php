<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['open', 'in_progress', 'finish', 'expired'];

        foreach ($statuses as $status) {
            TicketStatus::firstOrCreate(['status_name' => $status]);
        }
    }
}
