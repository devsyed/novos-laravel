<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use App\Models\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{

    protected $model = File::class;

    public function definition(): array
    {
        return [
            'file_name' => $this->faker->word,
            'ref_name' => $this->faker->uuid . '.txt',
        ];
    }
}
