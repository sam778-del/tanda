<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Savings\Models\SmartGoal;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'status' => $this->faker->randomElement([User::ENABLE, User::DISABLE]),
            'phone_no' => $this->faker->e164PhoneNumber
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function withWallet(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            //create a wallet for the user
            $user->wallet()->create([
                'user_id' => $user->id,
                'initial_amount' => 0,
                'actual_amount' => 0,
            ]);

            //create default system goal
            $system_goal_data = [
                'user_id' => $user->id,
                'name' => 'Reserve Funds',
                'colour_code' => 'ff0030',
                'can_delete' => false
            ];

            //create goal
            $smartGoal = $user->smartGoal()->create($system_goal_data);

            //create goal wallet
            $user->savingsWallet()->create([
                'initial_amount' => 0,
                'actual_amount' => 0,
                'user_id' => $user->id,
                'smart_goal_id' => $smartGoal->id
            ]);
        });
    }
}
