@extends('layouts.app')

@section('title')
    <title>Recruitment Platform</title>
@endsection

@section('content')
    <div class="container timeline-page">

        {{-- Header --}}
        <div class="page-header">
            <div>
                <h2>Recruitment Platform</h2>
                <p>Timeline - Track every candidate's recruitment journey</p>
            </div>

            <a href="{{ route('timeline.create') }}" class="btn btn-dark">
                + New
            </a>
        </div>

        {{-- Timelines --}}
        <div class="timeline-list">

            @foreach ($timelines as $timeline)
                {{-- Default κατάσταση: Κλειστή (δεν έχει την κλάση is-open) --}}
                <div class="timeline-card">

                    {{-- Card Header --}}
                    <div class="timeline-card-header">

                        <div class="people-info">

                            <div class="person">
                                <div class="person-icon recruiter-icon">
                                    R
                                </div>

                                <div>
                                    <small>Recruiter</small>
                                    <strong>
                                        {{ $timeline->recruiter_name . ' ' . $timeline->recruiter_surname }}
                                    </strong>
                                </div>
                            </div>

                            <div class="header-divider"></div>

                            <div class="person">
                                <div class="person-icon candidate-icon">
                                    C
                                </div>

                                <div>
                                    <small>Candidate</small>
                                    <strong>
                                        {{ $timeline->candidate_name . ' ' . $timeline->candidate_surname }}
                                    </strong>
                                </div>
                            </div>

                        </div>

                        <div class="header-right">

                            @php
                                $latestStep = $timeline->steps()->latest()->first();
                                $latestStatus = $latestStep ? $latestStep->current_status : 'Pending';
                                $latestCategory = $latestStep ? $latestStep->step_category : '';
                                $statusLower = strtolower($latestStatus);
                            @endphp

                            {{-- Timeline Status Badge με Status και Category --}}
                            <div class="timeline-status-badge {{ !$latestStep ? 'empty' : '' }}">
                                <span class="status-dot {{ $statusLower }}"></span>
                                <span class="status-text">
                                    {{ $latestStep ? "{$latestStatus} - {$latestCategory}" : 'No Steps' }}
                                </span>
                            </div>

                            <div class="timeline-id">
                                #{{ $timeline->id }}
                            </div>

                            {{-- Arrow Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>

                    </div>

                    {{-- Το περιεχόμενο (κλειστό από default) --}}
                    <div class="timeline-card-body">

                        {{-- Add Step --}}
                        @if (count($timeline->steps) < 3 &&
                                $timeline->steps()->latest()->first()?->current_status == App\Enums\StatusCategory::COMPLETE->value)
                            <div class="add-step-wrapper">
                                <a href="{{ route('step.create', $timeline->id) }}" class="btn btn-dark btn-sm">
                                    + Next step
                                </a>
                            </div>
                        @endif

                        {{-- Timeline --}}
                        <div class="timeline-content">

                            <div class="timeline-title">
                                @if (count($timeline->steps))
                                    <span>Recruitment progress</span>
                                @else
                                    <span class="empty-title">No steps have been created yet</span>
                                @endif
                            </div>

                            @if (count($timeline->steps))
                                <div class="timeline">

                                    @foreach ($timeline->steps as $index => $step)
                                        <div class="step">

                                            <div class="step-number-wrapper">
                                                {{-- Δυναμική κλάση κύκλου ανάλογα με το status --}}
                                                @php
                                                    $statusLower = strtolower($step->current_status);
                                                    $circleClass = 'pending-circle';

                                                    if ($statusLower == 'complete' || $statusLower == 'completed') {
                                                        $circleClass = 'completed-circle';
                                                    } elseif ($statusLower == 'reject' || $statusLower == 'rejected') {
                                                        $circleClass = 'rejected-circle';
                                                    }
                                                @endphp
                                                <div class="circle {{ $circleClass }}">
                                                    {{ $index + 1 }}
                                                </div>
                                            </div>

                                            <div class="step-info">

                                                <span class="step-label">
                                                    STEP {{ $index + 1 }}
                                                </span>

                                                <h5>{{ $step->step_category }}</h5>

                                                <select name="current_status[{{ $step->id }}]"
                                                    class="form-select status-select status-{{ $statusLower }}"
                                                    {{ $step->current_status != 'Pending' ? 'disabled' : '' }}>

                                                    @foreach ($status_categories as $status_category)
                                                        <option value="{{ $status_category['id'] }}"
                                                            {{ $status_category['title'] == $step->current_status ? 'selected' : '' }}>

                                                            {{ $status_category['title'] }}

                                                        </option>
                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>
                            @endif

                        </div>

                    </div> {{-- Τέλος .timeline-card-body --}}

                </div>
            @endforeach

        </div>

    </div>

    <div id="toast-container" class="toast-container"></div>
@endsection

@section('styles')
    <style>
        .timeline-page {
            max-width: 1100px;
        }

        /* HEADER */

        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 35px;
            gap: 20px;
        }

        .eyebrow {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: #6b7280;
        }

        .page-header h1 {
            margin: 5px 0 5px;
            font-size: 34px;
            font-weight: 800;
            letter-spacing: -.7px;
            color: #111827;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
            font-size: 15px;
        }

        /* CARD */

        .timeline-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .04);
            transition: .2s ease;
        }

        .timeline-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, .07);
            transform: translateY(-1px);
        }

        /* CARD HEADER */

        .timeline-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 25px;
            background: #fafafa;
            border-bottom: 1px solid #eeeeee;
            cursor: pointer;
            user-select: none;
        }

        .timeline-card-header:hover {
            background: #f3f4f6;
        }

        .people-info {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .person {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .person-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: white;
            font-size: 14px;
        }

        .recruiter-icon {
            background: #111827;
        }

        .candidate-icon {
            background: #6b7280;
        }

        .person small {
            display: block;
            color: #9ca3af;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .person strong {
            display: block;
            margin-top: 2px;
            font-size: 14px;
            color: #1f2937;
        }

        .header-divider {
            height: 35px;
            width: 1px;
            background: #e5e7eb;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .timeline-id {
            background: #f3f4f6;
            color: #4b5563;
            padding: 7px 11px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 800;
        }

        /* STATUS BADGE ΕΠΑΝΩ ΔΕΞΙΑ */
        .timeline-status-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #e5e7eb;
            color: #374151;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .timeline-status-badge.empty {
            color: #9ca3af;
            background: #f9fafb;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #d1d5db;
        }

        .status-dot.complete,
        .status-dot.completed {
            background: #10b981;
        }

        .status-dot.reject,
        .status-dot.rejected {
            background: #ef4444;
        }

        .status-dot.pending {
            background: #f59e0b;
        }

        /* ICON TOGGLE (Βελάκι) */
        .toggle-icon {
            color: #6b7280;
            transition: transform 0.3s ease;
        }

        .timeline-card.is-open .toggle-icon {
            transform: rotate(180deg);
        }

        /* COLLAPSIBLE BODY */
        .timeline-card-body {
            display: none;
        }

        .timeline-card.is-open .timeline-card-body {
            display: block;
        }

        /* ADD STEP */
        .add-step-wrapper {
            padding: 18px 25px 0;
        }

        /* TIMELINE CONTENT */
        .timeline-content {
            padding: 28px 25px 35px;
        }

        .timeline-title {
            text-align: center;
            margin-bottom: 35px;
        }

        .timeline-title span {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #374151;
        }

        .empty-title {
            color: #9ca3af !important;
        }

        /* --- TIMELINE LINE --- */
        .timeline {
            display: flex;
            justify-content: space-between;
        }

        .step {
            position: relative;
            flex: 1;
            text-align: center;
            z-index: 1;
        }

        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 22px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #e5e7eb;
            z-index: -1;
        }

        .step-number-wrapper {
            height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* --- ΚΥΚΛΟΙ --- */
        .circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
            font-size: 14px;
            font-weight: 800;
            box-shadow: 0 0 0 6px white;
            transition: all 0.2s ease;
        }

        .completed-circle {
            border: 2px solid #10b981;
            color: #10b981;
        }

        .rejected-circle {
            border: 2px solid #ef4444 !important;
            color: #ef4444 !important;
        }

        .pending-circle {
            border: 2px solid #6b7280;
            color: #4b5563;
        }

        /* --- ΤΥΠΟΓΡΑΦΙΑ ΒΗΜΑΤΟΣ --- */
        .step-info {
            margin-top: 12px;
            padding: 0 4px;
        }

        .step-label {
            font-size: 10px;
            color: #9ca3af;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .step-info h5 {
            margin: 4px 0 12px;
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.2;
            min-height: 31px;
        }

        /* --- DROPDOWNS --- */
        .status-select {
            width: 100%;
            max-width: 130px;
            margin: auto;
            text-align: center;
            text-align-last: center;
            font-size: 11.5px;
            font-weight: 700;
            padding: 6px 20px 6px 12px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background-color: white;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
        }

        .status-select:disabled {
            background-color: #f9fafb;
            color: #6b7280;
            border-color: #f3f4f6;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {

            .page-wrapper {
                padding: 25px 12px;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-header h1 {
                font-size: 27px;
            }

            .timeline-card-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 18px;
            }

            .people-info {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }

            .header-divider {
                display: none;
            }

            .header-right {
                width: 100%;
                justify-content: space-between;
            }

            .timeline-status-badge {
                margin-right: auto;
            }

            .timeline {
                flex-direction: column;
                gap: 30px;
            }

            .step:not(:last-child)::after {
                top: 45px;
                bottom: -30px;
                left: 22px;
                width: 2px;
                height: auto;
            }

            .step {
                display: flex;
                align-items: flex-start;
                text-align: left;
            }

            .step-number-wrapper {
                min-width: 45px;
            }

            .step-info {
                margin-top: 0;
                padding-left: 15px;
            }

            .status-select {
                margin: 0;
            }
        }

        .toast-container {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            min-width: 250px;
            padding: 14px 18px;
            background: #111827;
            color: white;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .toast.show {
            opacity: 1 transform: translateY(0);
        }

        .toast.success {
            background: #10b981;
        }

        .toast.error {
            background: #ef4444;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- Toast Notification Helper ---
            function showToast(message, type = 'success') {
                const container = document.getElementById('toast-container');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.textContent = message;

                container.appendChild(toast);

                setTimeout(() => toast.classList.add('show'), 10);

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // --- 1. Toggle Accordion Logic ---
            document.querySelectorAll('.timeline-card-header').forEach(function(header) {
                header.addEventListener('click', function() {
                    const card = this.closest('.timeline-card');
                    card.classList.toggle('is-open');
                });
            });

            // --- 2. Status Change Logic (Άμεση Αλλαγή Χρωμάτων & UI) ---
            document.querySelectorAll('select[name^="current_status"]').forEach(function(selectElement) {

                selectElement.addEventListener('change', function() {

                    const stepId = this.name.match(/\d+/)[0];
                    const newStatus = this.value;
                    const selectedOptionText = this.options[this.selectedIndex].text.trim()
                        .toLowerCase();
                    const currentSelect = this;

                    const stepItem = currentSelect.closest('.step');
                    const circle = stepItem ? stepItem.querySelector('.circle') : null;
                    const card = currentSelect.closest('.timeline-card');
                    const badgeDot = card ? card.querySelector('.status-dot') : null;

                    const xhr = new XMLHttpRequest();

                    xhr.open('POST', '{{ route('status.store') }}', true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                    xhr.onreadystatechange = function() {
                        if (xhr.status === 200) {
                            showToast('Status updated successfully', 'success');

                            currentSelect.disabled = true;
                            currentSelect.classList.remove('status-reject', 'status-rejected',
                                'status-complete', 'status-completed');

                            const stepTitleElement = stepItem.querySelector('h5');
                            const stepName = stepTitleElement ? stepTitleElement.textContent
                                .trim().toLowerCase() : '';
                            const totalSteps = card.querySelectorAll('.step').length;

                            if (selectedOptionText.includes('reject')) {
                                currentSelect.classList.add('status-reject');
                                if (circle) {
                                    circle.classList.remove('pending-circle',
                                        'completed-circle');
                                    circle.classList.add('rejected-circle');
                                }
                                if (badgeDot) {
                                    badgeDot.className = 'status-dot reject';
                                }
                            } else if (selectedOptionText.includes('complete')) {
                                currentSelect.classList.add('status-complete');
                                if (circle) {
                                    circle.classList.remove('pending-circle',
                                        'rejected-circle');
                                    circle.classList.add('completed-circle');
                                }
                                if (badgeDot) {
                                    badgeDot.className = 'status-dot complete';
                                }
                            }

                            // --- ΕΛΕΓΧΟΣ ΓΙΑ ΤΑΞΙΝΟΜΗΣΗ / ΑΦΑΙΡΕΣΗ ΚΟΥΜΠΙΟΥ ---
                            // Αν είναι προσφορά (offer) ή έχουμε φτάσει τα 3 βήματα, αφαιρούμε το κουμπί αν υπάρχει
                            if (stepName.includes('offer') || totalSteps >= 3 || !
                                selectedOptionText.includes('complete')) {
                                const existingWrapper = card.querySelector('.add-step-wrapper');
                                if (existingWrapper) {
                                    existingWrapper.remove();
                                }
                            } else {
                                // Διαφορετικά, αν επιτρέπεται, το εμφανίζουμε αν δεν υπάρχει ήδη
                                let addStepWrapper = card.querySelector('.add-step-wrapper');
                                if (!addStepWrapper) {
                                    addStepWrapper = document.createElement('div');
                                    addStepWrapper.className = 'add-step-wrapper';
                                    const timelineId = card.querySelector('.timeline-id')
                                        .textContent.replace('#', '').trim();
                                    addStepWrapper.innerHTML = `
                <a href="/step/new/${timelineId}" class="btn btn-dark btn-sm">
                    + Next step
                </a>
            `;
                                    const cardBody = card.querySelector('.timeline-card-body');
                                    cardBody.insertBefore(addStepWrapper, cardBody.firstChild);
                                }
                            }

                            // Μέσα στο block επιτυχίας (xhr.status === 200):
                            if (badgeDot) {
                                const badgeContainer = badgeDot.closest(
                                    '.timeline-status-badge');
                                const statusTextSpan = badgeContainer ? badgeContainer
                                    .querySelector('.status-text') : null;

                                if (selectedOptionText.includes('reject')) {
                                    badgeDot.className = 'status-dot reject';
                                } else if (selectedOptionText.includes('complete')) {
                                    badgeDot.className = 'status-dot complete';
                                } else {
                                    badgeDot.className = 'status-dot pending';
                                }

                                if (statusTextSpan) {
                                    const statusTitle = currentSelect.options[currentSelect
                                        .selectedIndex].text.trim();
                                    const stepTitleElement = stepItem.querySelector('h5');
                                    const stepCategory = stepTitleElement ? stepTitleElement
                                        .textContent.trim() : '';

                                    statusTextSpan.textContent =
                                        `${statusTitle} - ${stepCategory}`;
                                    badgeContainer.classList.remove('empty');
                                }
                            }
                        }
                    };

                    // Αν το status είναι complete και υπάρχουν λιγότερα από 3 βήματα, εμφανίζουμε το κουμπί Add next step
                    if (selectedOptionText.includes('complete')) {
                        let addStepWrapper = card.querySelector('.add-step-wrapper');
                        if (!addStepWrapper) {
                            addStepWrapper = document.createElement('div');
                            addStepWrapper.className = 'add-step-wrapper';

                            // Δημιουργία του link με βάση το timeline id
                            const timelineId = card.querySelector('.timeline-id').textContent
                                .replace('#', '').trim();
                            addStepWrapper.innerHTML = `
                                <a href="/step/new/${timelineId}" class="btn btn-dark btn-sm">
                                    + Next step
                                </a>
                            `;

                            // Το τοποθετούμε στην αρχή του body της καρτέλας
                            const cardBody = card.querySelector('.timeline-card-body');
                            cardBody.insertBefore(addStepWrapper, cardBody.firstChild);
                        }
                    } else if (selectedOptionText.includes('complete')) {
                        currentSelect.classList.add('status-complete');
                        if (circle) {
                            circle.classList.remove('pending-circle', 'rejected-circle');
                            circle.classList.add('completed-circle');
                        }
                        if (badgeDot) {
                            badgeDot.className = 'status-dot complete';
                        }

                        // --- ΕΜΦΑΝΙΣΗ ΚΟΥΜΠΙΟΥ ΜΟΝΟ ΑΝ ΔΕΝ ΕΙΣΑΙ ΣΤΟ OFFER / < 3 ΒΗΜΑΤΑ ---
                        const stepTitleElement = stepItem.querySelector('h5');
                        const stepName = stepTitleElement ? stepTitleElement.textContent.trim()
                            .toLowerCase() : '';
                        const totalSteps = card.querySelectorAll('.step').length;

                        // Αν το βήμα ΔΕΝ είναι offer και τα συνολικά βήματα είναι λιγότερα από 3
                        if (!stepName.includes('offer') && totalSteps < 3) {
                            let addStepWrapper = card.querySelector('.add-step-wrapper');
                            if (!addStepWrapper) {
                                addStepWrapper = document.createElement('div');
                                addStepWrapper.className = 'add-step-wrapper';
                                const timelineId = card.querySelector('.timeline-id').textContent
                                    .replace('#', '').trim();
                                addStepWrapper.innerHTML = `
                                    <a href="/timeline/${timelineId}/step/create" class="btn btn-dark btn-sm">
                                        + Next step
                                    </a>
                                `;
                                const cardBody = card.querySelector('.timeline-card-body');
                                cardBody.insertBefore(addStepWrapper, cardBody.firstChild);
                            }
                        }
                    }

                    xhr.send(JSON.stringify({
                        step_id: stepId,
                        status_category: newStatus
                    }));

                });

            });

        });
    </script>
@endsection
