<?php

namespace App\Http\Controllers;

use App\Enums\StatusCategory;
use App\Models\Step;
use App\Models\StepStatusHistory;
use App\Resources\StepStatusHistoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class StepStatusHistoryController extends Controller
{
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $cannotProceed = $this->checkRestrictions($request['step_id']);

        if ($cannotProceed) {
            return $cannotProceed;
        }

        $status = StepStatusHistory::create([
            'step_id' => $request['step_id'],
            'status_category' => $request['status_category'],
        ]);

        return [
            'message' => 'Created Successfully',
            'entry' => new StepStatusHistoryResource($status),
        ];
    }

    private function validateRequest($request): void
    {
        $request->validate([
            'step_id' => ['required', 'exists:steps,id'],
            'status_category' => ['required', new Enum(StatusCategory::class)],
        ]);
    }

    private function checkRestrictions($step_id): JsonResponse|bool
    {
        $step = Step::find($step_id);
        $statuses = $step->statuses->pluck('status_category')->toArray();

        if (in_array(StatusCategory::COMPLETE->value, $statuses) || in_array(StatusCategory::REJECT->value, $statuses)) {
            return response()->json([
                'message' => 'Η κατάσταση αλλάζει μόνο από «Σε εξέλιξη».'
            ], 422);
        }

        return false;
    }
}
