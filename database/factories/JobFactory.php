<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElements([
                'Algorithm Developer',
                'API Developer',
                'Back-End Developer',
                'Business Analyst',
                'Computer Support Specialist',
                'Database Administrator',
                'Database Manager',
                'Development Team Leader',
                'Front-End Developer',
                'Full-Stack Developer',
                'Information Systems Manager',
                'Junior Developer',
                'Junior Front-End Developer',
                'Junior Full-Stack Developer',
                'Junior Back-End Developer',
                'Junior Web Developer',
                'Network Admin',
                'Network Systems Analyst',
                'Project Manager',
                'Senior Developer',
                'Senior Front-End Developer',
                'Senior Full-Stack Developer',
                'Senior Back-End Developer',
                'Senior Web Developer',
                'Software Engineer',
                'Sys Admin',
                'System Administrator',
                'Systems Administrator',
                'Systems Analyst',
                'UI Designer',
                'UI/UX Designer',
                'UX Designer',
                'UI Developer',
                'UI/UX Developer',
                'UX Developer',
                'Web Developer'
            ]),
            'company' => fake()->company(),
            'site' => fake()->url(),
            'desc' => fake()->sentence(3)
        ];
    }
}
