<?php

namespace Database\Factories;

use App\Models\Transporter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransporterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transporter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'gstin' => $this->faker->ean13,
            'contact_person' => $this->faker->name,
            'contact_number' => $this->faker->phoneNumber,
        ];
    }
}
