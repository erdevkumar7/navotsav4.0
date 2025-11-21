<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::factory()
            ->count(50)
            ->create()
            ->each(function ($event) {
                DB::table('event_banners')->insert([
                    'event_id' => $event->id,
                    'banner'   => 'event_banners/NzkidT0wE0HeDeLGrTPvSjnOcJjkvJ7sHRT4iElC.jpg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('multiple_prices')->insert(
                    [
                        'event_id' => $event->id,
                        'price'   => $event->ticket_price,
                        'quantity' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                DB::table('multiple_prices')->insert([
                    'event_id' => $event->id,
                    'price'   => ($event->ticket_price * 2) - 20,
                    'quantity' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        $this->command->info('✅ 50 random events inserted successfully.');
    }
}
