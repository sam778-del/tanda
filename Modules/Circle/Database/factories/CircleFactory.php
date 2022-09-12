<?php
namespace Modules\Circle\Database\factories;

use App\Models\User;
use App\Traits\CircleTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Circle\Models\Circle;
use Modules\Circle\Models\CircleMember;

class CircleFactory extends Factory
{
    use CircleTrait;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Circle::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'circle_amount' => $this->faker->numberBetween(1000, 10000),
            'circle_size' => $this->faker->randomDigitNotNull,
            'start_date' =>  now()->toDateString(),
            'end_date' => $this->faker->date()
        ];
    }

    public function privateCircle(): CircleFactory
    {
        return $this->state([
            'type' => Circle::PRIVATE,
            'user_id' => User::factory()->create()->id,
        ]);
    }

    public function publicCircle(): CircleFactory
    {
        return $this->state([
            'type' => Circle::PUBLIC,
            'user_id' => null
        ]);
    }

    public function withCircleMembers()
    {
        return $this->afterCreating(function (Circle $circle) {
            for ($i = 0; $i < (int) $circle->circle_size; $i++) {
                CircleMember::create([
                    'circle_id' => $circle->id,
                    'slot_number' => $i + 1,
                    'status' => CircleMember::PENDING,
                    'payout_date' => $this->processPayoutDate($circle, $i),
                ]);
            }
        });
    }
}

