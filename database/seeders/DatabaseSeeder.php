<?php

namespace Database\Seeders;

use App\Enums\StatusCategory;
use App\Enums\StepCategory;
use App\Models\Step;
use App\Models\StepStatusHistory;
use App\Models\Timeline;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Ρεαλιστικό στιγμιότυπο μιας διαδικασίας προσλήψεων σε εξέλιξη.
     *
     * Κάθε εγγραφή παίρνει ρητό created_at με απόσταση μεταξύ τους, επειδή τα
     * Step::current_status και Timeline::latestStepCategory() ταξινομούν με latest().
     * Χωρίς διακριτούς χρόνους η σειρά είναι απρόβλεπτη.
     */
    public function run(): void
    {
        $pending = StatusCategory::PENDING->value;
        $complete = StatusCategory::COMPLETE->value;
        $reject = StatusCategory::REJECT->value;

        $first = StepCategory::FIRST_INTERVIEW->value;
        $tech = StepCategory::TECH_ASSESSMENT->value;
        $offer = StepCategory::OFFER->value;

        $timelines = [
            [
                'recruiter' => ['Ελένη', 'Παπαδοπούλου'],
                'candidate' => ['Γιώργος', 'Δημητρίου'],
                'steps' => [[$first, $complete], [$tech, $complete], [$offer, $pending]],
            ],
            [
                'recruiter' => ['Ελένη', 'Παπαδοπούλου'],
                'candidate' => ['Μαρία', 'Κωνσταντίνου'],
                'steps' => [[$first, $complete], [$tech, $pending]],
            ],
            [
                'recruiter' => ['Νίκος', 'Αντωνιάδης'],
                'candidate' => ['Δημήτρης', 'Βλάχος'],
                'steps' => [[$first, $pending]],
            ],
            [
                'recruiter' => ['Νίκος', 'Αντωνιάδης'],
                'candidate' => ['Σοφία', 'Νικολάου'],
                'steps' => [[$first, $complete], [$tech, $reject]],
            ],
            [
                'recruiter' => ['Ελένη', 'Παπαδοπούλου'],
                'candidate' => ['Ανδρέας', 'Στεφανίδης'],
                'steps' => [[$first, $complete], [$tech, $complete], [$offer, $complete]],
            ],
            [
                'recruiter' => ['Νίκος', 'Αντωνιάδης'],
                'candidate' => ['Κατερίνα', 'Μαυρίδη'],
                'steps' => [[$first, $reject]],
            ],
            [
                'recruiter' => ['Ελένη', 'Παπαδοπούλου'],
                'candidate' => ['Θανάσης', 'Οικονόμου'],
                'steps' => [[$first, $pending]],
            ],
        ];

        DB::transaction(function () use ($timelines) {
            // Ο πιο παλιός φάκελος ξεκίνησε πριν από 24 ημέρες· κάθε επόμενος 3 ημέρες αργότερα.
            $daysAgo = 24;

            foreach ($timelines as $data) {
                $timelineCreatedAt = now()->subDays($daysAgo);

                $timeline = Timeline::create([
                    'recruiter_name' => $data['recruiter'][0],
                    'recruiter_surname' => $data['recruiter'][1],
                    'candidate_name' => $data['candidate'][0],
                    'candidate_surname' => $data['candidate'][1],
                ]);

                $timeline->forceFill([
                    'created_at' => $timelineCreatedAt,
                    'updated_at' => $timelineCreatedAt,
                ])->save();

                foreach ($data['steps'] as $index => [$category, $status]) {
                    // Κάθε βήμα 4 ημέρες μετά το προηγούμενο, ώστε το latest() να είναι σταθερό.
                    $stepCreatedAt = $timelineCreatedAt->copy()->addDays($index * 4);

                    $step = Step::create([
                        'timeline_id' => $timeline->id,
                        'step_category' => $category,
                    ]);

                    $step->forceFill([
                        'created_at' => $stepCreatedAt,
                        'updated_at' => $stepCreatedAt,
                    ])->save();

                    // Κάθε βήμα ξεκινά πάντα ως Σε εξέλιξη· αν η τελική κατάσταση
                    // είναι άλλη, καταγράφεται ως δεύτερη εγγραφή δύο ημέρες μετά.
                    $this->recordStatus($step->id, StatusCategory::PENDING->value, $stepCreatedAt);

                    if ($status !== StatusCategory::PENDING->value) {
                        $this->recordStatus($step->id, $status, $stepCreatedAt->copy()->addDays(2));
                    }
                }

                $daysAgo -= 3;
            }
        });
    }

    private function recordStatus(int $stepId, string $status, $createdAt): void
    {
        $entry = StepStatusHistory::create([
            'step_id' => $stepId,
            'status_category' => $status,
        ]);

        $entry->forceFill([
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ])->save();
    }
}
