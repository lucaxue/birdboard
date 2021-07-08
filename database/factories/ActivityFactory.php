<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Project;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'description' => $this->faker->sentence(),
        ];
    }
}
