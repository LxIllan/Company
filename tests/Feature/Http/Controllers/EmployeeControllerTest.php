<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\EmployeeController
 */
final class EmployeeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $employees = Employee::factory()->count(3)->create();

        $response = $this->get(route('employees.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\EmployeeController::class,
            'store',
            \App\Http\Requests\EmployeeStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $name = $this->faker->name();
        $age = $this->faker->numberBetween(-10000, 10000);
        $salary = $this->faker->randomFloat(2, 0, 999999.99);
        $email = $this->faker->safeEmail();
        $address = $this->faker->word();
        $phone = $this->faker->phoneNumber();
        $department = Department::factory()->create();

        $response = $this->post(route('employees.store'), [
            'name' => $name,
            'age' => $age,
            'salary' => $salary,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'department_id' => $department->id,
        ]);

        $employees = Employee::query()
            ->where('name', $name)
            ->where('age', $age)
            ->where('salary', $salary)
            ->where('email', $email)
            ->where('address', $address)
            ->where('phone', $phone)
            ->where('department_id', $department->id)
            ->get();
        $this->assertCount(1, $employees);
        $employee = $employees->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->get(route('employees.show', $employee));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\EmployeeController::class,
            'update',
            \App\Http\Requests\EmployeeUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $employee = Employee::factory()->create();
        $name = $this->faker->name();
        $age = $this->faker->numberBetween(-10000, 10000);
        $salary = $this->faker->randomFloat(2, 0, 999999.99);
        $email = $this->faker->safeEmail();
        $address = $this->faker->word();
        $phone = $this->faker->phoneNumber();
        $department = Department::factory()->create();

        $response = $this->put(route('employees.update', $employee), [
            'name' => $name,
            'age' => $age,
            'salary' => $salary,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'department_id' => $department->id,
        ]);

        $employee->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($name, $employee->name);
        $this->assertEquals($age, $employee->age);
        $this->assertEquals($salary, $employee->salary);
        $this->assertEquals($email, $employee->email);
        $this->assertEquals($address, $employee->address);
        $this->assertEquals($phone, $employee->phone);
        $this->assertEquals($department->id, $employee->department_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->delete(route('employees.destroy', $employee));

        $response->assertNoContent();

        $this->assertModelMissing($employee);
    }
}
