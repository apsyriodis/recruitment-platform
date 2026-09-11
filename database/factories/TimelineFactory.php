<?php

namespace Database\Factories;

use App\Models\Timeline;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimelineFactory extends Factory
{
    protected $model = Timeline::class;

    public function definition(): array
    {
        $firstNames = ['Ελένη', 'Νίκος', 'Μαρία', 'Γιώργος', 'Σοφία', 'Δημήτρης', 'Κατερίνα', 'Ανδρέας'];
        $lastNames = ['Παπαδοπούλου', 'Αντωνιάδης', 'Κωνσταντίνου', 'Δημητρίου', 'Νικολάου', 'Βλάχος', 'Μαυρίδη', 'Στεφανίδης'];

        return [
            'candidate_name' => $this->faker->randomElement($firstNames),
            'candidate_surname' => $this->faker->randomElement($lastNames),
            'recruiter_name' => $this->faker->randomElement($firstNames),
            'recruiter_surname' => $this->faker->randomElement($lastNames),
        ];
    }
}
