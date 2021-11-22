<?php

namespace Database\Factories;

use App\Models\Consumer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsumerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Consumer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      return [
            'consumer_name' => $this->faker->company,
            'gstin' => $this->faker->ean13,
            'contact_person' => $this->faker->name,
            'contact_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'pincode' => $this->faker->postcode,
            'state_id' => 12
        ];
    }
}
