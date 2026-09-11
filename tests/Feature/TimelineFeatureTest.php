<?php

namespace Tests\Feature;

use App\Models\Timeline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_a_new_timeline()
    {
        $payload = [
            'recruiter_name' => 'John',
            'recruiter_surname' => 'Doe',
            'candidate_name' => 'Jane',
            'candidate_surname' => 'Smith',
        ];

        $this->postJson('/api/timeline', $payload);

        $this->assertDatabaseHas('timelines', $payload);
    }

    public function test_can_validate_request()
    {
        $response = $this->postJson('/api/timeline', []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['recruiter_name', 'recruiter_surname', 'candidate_name', 'candidate_surname']);
    }

    public function test_can_get_specific_timeline()
    {
        $timeline = Timeline::factory()->create();

        $response = $this->getJson('/api/timeline/' . $timeline->id);

        $response->assertStatus(200);
    }

    public function test_accepts_greek_names()
    {
        $response = $this->post('/timeline', [
            'recruiter_name' => 'Ελένη',
            'recruiter_surname' => 'Παπαδοπούλου',
            'candidate_name' => 'Γιώργος',
            'candidate_surname' => 'Δημητρίου',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('timelines', [
            'candidate_name' => 'Γιώργος',
            'candidate_surname' => 'Δημητρίου',
        ]);
    }

    public function test_still_rejects_digits_in_names()
    {
        $response = $this->post('/timeline', [
            'recruiter_name' => 'Ελένη123',
            'recruiter_surname' => 'Παπαδοπούλου',
            'candidate_name' => 'Γιώργος',
            'candidate_surname' => 'Δημητρίου',
        ]);

        $response->assertSessionHasErrors(['recruiter_name']);
    }
}
