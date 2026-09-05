@extends('layouts.app')

@section('title')
    <title>New Step</title>
@endsection

@section('content')
    <div class="container new-step-page">

        <div class="form-card">

            {{-- Header --}}
            <div class="form-header">

                <div class="form-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>

                <div class="form-header-text">
                    <span class="eyebrow">RECRUITMENT TIMELINE</span>
                    <h1>Create a new step</h1>
                </div>

            </div>

            {{-- Form --}}
            <form action="{{ route('step.store', ['timeline_id' => $timeline->id]) }}" method="POST">

                @csrf

                <div class="form-body">

                    @php
                        $stepCategory =
                            $timeline->latestStepCategory() == App\Enums\StepCategory::FIRST_INTERVIEW->value
                                ? App\Enums\StepCategory::TECH_ASSESSMENT->value
                                : App\Enums\StepCategory::OFFER->value;
                    @endphp

                    {{-- Step Category --}}
                    <div class="form-group">

                        <label>
                            Step
                        </label>

                        <div class="fixed-field">
                            <span>{{ $stepCategory }}</span>
                        </div>

                        <input type="hidden" name="step_category" value="{{ $stepCategory }}">

                    </div>

                    {{-- Status --}}
                    <div class="form-group">

                        <label for="status_category">
                            Initial Status
                        </label>

                        <select class="form-select custom-select" id="status_category" name="status_category">

                            @foreach ($status_categories as $status_category)
                                <option value="{{ $status_category['id'] }}">
                                    {{ $status_category['title'] }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <input type="hidden" name="timeline_id" value="{{ $timeline->id }}">

                </div>

                {{-- Footer --}}
                <div class="form-footer">

                    <a href="{{ url()->previous() }}" class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-dark">
                        Create
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection


@section('styles')
    <style>
        .new-step-page {
            max-width: 650px;
            padding-top: 40px;
            padding-bottom: 40px;
        }


        /* CARD */

        .form-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }


        /* HEADER */

        .form-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 30px;
            background: #ffffff;
            border-bottom: 1px solid #f3f4f6;
        }

        .form-icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #111827;
            color: #ffffff;
            border-radius: 12px;
        }

        .form-header-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: 1.5px;
            font-weight: 800;
            color: #9ca3af;
            text-transform: uppercase;
        }

        .form-header h1 {
            margin: 0;
            font-size: 25px;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
        }

        .form-header p {
            margin: 4px 0 0 0;
            color: #6b7280;
            font-size: 15px;
            font-weight: 500;
        }


        /* FORM */

        .form-body {
            padding: 35px 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }


        /* FIXED STEP CATEGORY */

        .fixed-field {
            min-height: 48px;
            display: flex;
            align-items: center;
            padding: 10px 16px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            color: #374151;
            font-size: 14px;
            font-weight: 700;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
        }


        /* SELECT */

        .custom-select {
            width: 100%;
            min-height: 48px;
            padding: 10px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background-color: #ffffff;
            font-size: 14px;
            color: #1f2937;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .custom-select:focus {
            border-color: #d1d5db;
            outline: none;
            box-shadow: 0 0 0 4px rgba(243, 244, 246, 1);
        }


        /* FOOTER */

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 24px 30px;
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-dark {
            background: #111827;
            border: 1px solid #111827;
            color: #ffffff;
            box-shadow: 0 1px 2px rgba(17, 24, 39, 0.1);
        }

        .btn-dark:hover {
            background: #1f2937;
            border-color: #1f2937;
        }

        .btn-light {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: #374151;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .btn-light:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }


        /* RESPONSIVE */

        @media (max-width: 576px) {

            .new-step-page {
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .form-header {
                padding: 24px 20px;
                gap: 15px;
            }

            .form-icon {
                width: 42px;
                height: 42px;
            }

            .form-header h1 {
                font-size: 22px;
            }

            .form-header p {
                font-size: 13px;
            }

            .form-body {
                padding: 25px 20px;
            }

            .form-footer {
                padding: 20px;
            }

        }
    </style>
@endsection
