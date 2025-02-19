<?php

namespace Database\Factories;

use App\Models\BreakModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreakModelFactory extends Factory
{
    protected $model = BreakModel::class;

    public function definition()
    {
        return [
            'break_start' => $this->faker->dateTimeThisYear(),
            'break_end' => $this->faker->dateTimeThisYear(),
        ];
    }
}