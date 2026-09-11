<?php

namespace Tests\Feature;

use App\Enums\StatusCategory;
use App\Models\Step;
use App\Models\Timeline;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeds_a_realistic_mix()
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(7, Timeline::count());

        $statuses = Step::all()->map->current_status;

        $this->assertTrue($statuses->contains(StatusCategory::PENDING->value));
        $this->assertTrue($statuses->contains(StatusCategory::COMPLETE->value));
        $this->assertTrue($statuses->contains(StatusCategory::REJECT->value));
    }

    public function test_steps_have_distinct_creation_times_so_latest_is_deterministic()
    {
        $this->seed(DatabaseSeeder::class);

        $timeline = Timeline::has('steps', '>=', 2)->first();

        $this->assertNotNull($timeline, 'Χρειάζεται τουλάχιστον ένα timeline με 2+ βήματα.');

        $timestamps = $timeline->steps->pluck('created_at')->map->timestamp;

        $this->assertSame(
            $timestamps->count(),
            $timestamps->unique()->count(),
            'Τα βήματα πρέπει να έχουν διακριτά created_at, αλλιώς το latest() είναι απρόβλεπτο.'
        );
    }
}
