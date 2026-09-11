<?php

namespace Tests\Unit;

use App\Enums\StatusCategory;
use App\Enums\StepCategory;
use PHPUnit\Framework\TestCase;

class EnumLabelTest extends TestCase
{
    public function test_status_labels_are_greek()
    {
        $this->assertSame('Σε εξέλιξη', StatusCategory::PENDING->label());
        $this->assertSame('Ολοκληρώθηκε', StatusCategory::COMPLETE->label());
        $this->assertSame('Απορρίφθηκε', StatusCategory::REJECT->label());
    }

    public function test_step_labels_are_greek()
    {
        $this->assertSame('Πρώτη Συνέντευξη', StepCategory::FIRST_INTERVIEW->label());
        $this->assertSame('Τεχνική Αξιολόγηση', StepCategory::TECH_ASSESSMENT->label());
        $this->assertSame('Πρόταση Εργασίας', StepCategory::OFFER->label());
    }

    public function test_values_stay_english_for_the_database()
    {
        $this->assertSame(['Pending', 'Complete', 'Reject'], StatusCategory::values());
        $this->assertSame(
            ['First Interview', 'Tech Assessment', 'Offer'],
            StepCategory::values()
        );
    }

    public function test_to_array_keeps_value_in_id_and_greek_in_title()
    {
        $this->assertSame([
            ['id' => 'Pending', 'title' => 'Σε εξέλιξη'],
            ['id' => 'Complete', 'title' => 'Ολοκληρώθηκε'],
            ['id' => 'Reject', 'title' => 'Απορρίφθηκε'],
        ], StatusCategory::toArray());
    }
}
