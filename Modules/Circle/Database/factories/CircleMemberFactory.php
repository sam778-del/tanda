<?php
namespace Modules\Circle\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Circle\Models\Circle;
use Modules\Circle\Models\CircleMember;

class CircleMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Circle\Models\CircleMember::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'circle_id' => Circle::factory()->create()->id,
            'payout_date' => '',
            'slot_number' => '',
        ];
    }

    public function pending(): CircleMemberFactory
    {
        return $this->state([
            'status' => CircleMember::PENDING
        ]);
    }

    public function accepted(): CircleMemberFactory
    {
        return $this->state([
            'status' => CircleMember::ACCEPTED
        ]);
    }
}

