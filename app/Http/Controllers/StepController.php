<?php

namespace App\Http\Controllers;

use App\Enums\StatusCategory;
use App\Enums\StepCategory;
use App\Models\Step;
use App\Models\StepStatusHistory;
use App\Models\Timeline;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class StepController extends Controller
{
    public function create(Request $request)
    {
        return view('new-step', [
            'timeline' => Timeline::find($request['timeline_id']),
            'status_categories' => StatusCategory::toArray(),
        ]);
    }

    public function store(Request $request, $timeline_id)
    {
        $request['timeline_id'] = $timeline_id;

        $this->validateRequest($request);

        $cannotProceed = $this->checkRestrictions($request);

        if ($cannotProceed) {
            return $cannotProceed;
        }

        $this->createStepAndHistory($request);

        session()->flash('success', 'Το βήμα καταχωρήθηκε.');

        return redirect()->route('home');
    }

    private function validateRequest($request): void
    {
        $request->validate([
            'timeline_id' => ['required', 'exists:timelines,id'],
            'step_category' => ['required', new Enum(StepCategory::class)],
            'status_category' => ['required', new Enum(StatusCategory::class)],
        ]);
    }

    private function createStepAndHistory($request): void
    {
        $step = Step::create([
            'timeline_id' => $request['timeline_id'],
            'step_category' => $request['step_category'],
        ]);

        StepStatusHistory::create([
            'step_id' => $step->id,
            'status_category' => $request['status_category'],
        ]);
    }

    private function checkRestrictions($request): JsonResponse|RedirectResponse|bool
    {
        $timeline = Timeline::find($request['timeline_id']);

        if (count($timeline->steps) >= 3) {
            return $this->refuse($request, 'Μια διαδικασία δεν μπορεί να έχει πάνω από 3 βήματα.');
        }

        if (in_array($request['step_category'], $timeline->stepCategories())) {
            return $this->refuse($request, 'Το βήμα αυτό έχει ήδη καταχωρηθεί για τη συγκεκριμένη διαδικασία.');
        }

        return false;
    }

    private function refuse($request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 422);
        }

        return redirect()->back()->with('error', $message);
    }
}
