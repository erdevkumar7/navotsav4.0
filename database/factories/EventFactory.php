<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::now()->addDays($this->faker->numberBetween(1, 10));
        $endDate   = (clone $startDate)->addDays($this->faker->numberBetween(5, 15));
        $drawTime  = (clone $endDate)->addDay();

        return [
            'title' => $this->faker->catchPhrase(),
            'contest_no' => strtoupper(Str::random(4)),
            'description' => $this->faker->paragraph(),
            'ticket_price' => $this->faker->randomFloat(0, 10, 500),
            'multiple_price' => true,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'draw_time' => $drawTime,
            'visiblity' => $this->faker->randomElement(['offline', 'online', 'both']),
            'category_id' => $this->faker->randomElement([
                1,
                2,
                3,
                4
            ]),
            'location' => $this->faker->city(),
            'ticket_quantity' => $this->faker->numberBetween(50, 500),
            'is_publish' => true,
            'created_by' => User::whereIn('user_type', [1, 3])->inRandomOrder()->first()?->id,
        ];
    }
}
