<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;
use App\Models\Employee;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'age' => $this->faker->numberBetween(20, 60),
            'salary' => $this->faker->randomFloat(2, 0, 999999.99),
            'email' => $this->faker->safeEmail(),
            'address' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'phone' => $this->faker->phoneNumber(),
            'department_id' => Department::factory(),
        ];
    }
}
