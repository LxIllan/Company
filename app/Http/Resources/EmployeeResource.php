<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'salary' => $this->salary,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'department_id' => $this->department_id,
            'department' => DepartmentResource::make($this->whenLoaded('department')),
        ];
    }
}
