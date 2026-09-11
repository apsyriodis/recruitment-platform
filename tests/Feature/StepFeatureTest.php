<?php

namespace Tests\Feature;

use App\Enums\StatusCategory;
use App\Enums\StepCategory;
use App\Models\Timeline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StepFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_a_new_step_with_step_status_history()
    {
        $timeline = Timeline::factory()->create();

        $payload = [
            'step_category' => StepCategory::FIRST_INTERVIEW->value,
            'status_category' => StatusCategory::PENDING->value,
        ];

        $this->postJson("/api/step/{$timeline->id}", $payload);

        $this->assertDatabaseHas('steps', [
            'timeline_id' => $timeline->id,
            'step_category' => StepCategory::FIRST_INTERVIEW->value,
        ]);

        $this->assertDatabaseHas('step_status_history', [
            'status_category' => StatusCategory::PENDING->value,
        ]);
    }

    public function test_can_validate_request()
    {
        $response = $this->postJson('/api/step/1', []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['step_category', 'status_category']);
    }

    public function test_cannot_create_over_3_steps_for_a_timeline()
    {
        $timeline = Timeline::factory()->create();

        $step_categories = StepCategory::toArray();
        $status_categories = StatusCategory::toArray();

        for ($i = 0; $i < 3; $i++) {
            $payload = [
                'step_category' => $step_categories[$i]['id'],
                'status_category' => $status_categories[$i]['id'],
            ];

            $this->postJson("/api/step/{$timeline->id}", $payload);

            $this->assertDatabaseHas('steps', [
                'timeline_id' => $timeline->id,
                'step_category' => $step_categories[$i]['id'],
            ]);

            $this->assertDatabaseHas('step_status_history', [
                'status_category' => $status_categories[$i]['id'],
            ]);
        }

        $response = $this->postJson("/api/step/{$timeline->id}", $payload);

        $response->assertStatus(422);
    }

    public function test_cannot_create_an_existing_step_for_a_timeline()
    {
        $timeline = Timeline::factory()->create();

        $payload = [
            'step_category' => StepCategory::FIRST_INTERVIEW->value,
            'status_category' => StatusCategory::PENDING->value,
        ];

        for ($i = 1; $i < 3; $i++) {
            $response = $this->postJson("/api/step/{$timeline->id}", $payload);
        }

        $response->assertStatus(422);
    }

    public function test_web_form_redirects_back_with_error_instead_of_json()
    {
        $timeline = \App\Models\Timeline::factory()->create();

        // Το πρώτο βήμα υπάρχει ήδη μόνο όταν το timeline φτιαχτεί από τον controller,
        // οπότε εδώ το δημιουργούμε ρητά για να προκαλέσουμε διπλή κατηγορία.
        $step = \App\Models\Step::create([
            'timeline_id' => $timeline->id,
            'step_category' => StepCategory::FIRST_INTERVIEW->value,
        ]);

        \App\Models\StepStatusHistory::create([
            'step_id' => $step->id,
            'status_category' => StatusCategory::PENDING->value,
        ]);

        $response = $this->post("/step/{$timeline->id}", [
            'step_category' => StepCategory::FIRST_INTERVIEW->value,
            'status_category' => StatusCategory::PENDING->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
