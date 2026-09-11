@extends('layouts.app')

@section('title')
    <title>Νέα Διαδικασία</title>
@endsection

@section('content')
    <div class="container new-timeline-page">

        <div class="form-card">

            {{-- Header --}}
            <div class="form-header">

                <div class="form-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>

                <div class="form-header-text">
                    <span class="eyebrow">ΠΛΑΤΦΟΡΜΑ ΠΡΟΣΛΗΨΕΩΝ</span>
                    <h1>Νέα Διαδικασία</h1>
                    <p>Συμπληρώστε τα στοιχεία υπευθύνου και υποψηφίου.</p>
                </div>

            </div>

            {{-- Form --}}
            <form action="{{ route('timeline.store') }}" method="POST">

                @csrf

                <div class="form-body">

                    <div class="section-title">
                        Στοιχεία υπευθύνου
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="recruiter_name">Όνομα</label>
                                <input type="text" class="form-control" id="recruiter_name" name="recruiter_name"
                                    placeholder="π.χ. Ελένη" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="recruiter_surname">Επώνυμο</label>
                                <input type="text" class="form-control" id="recruiter_surname" name="recruiter_surname"
                                    placeholder="π.χ. Παπαδοπούλου" required>
                            </div>
                        </div>

                    </div>


                    <div class="section-title candidate-section">
                        Στοιχεία υποψηφίου
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="candidate_name">Όνομα</label>
                                <input type="text" class="form-control" id="candidate_name" name="candidate_name"
                                    placeholder="π.χ. Γιώργος" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="candidate_surname">Επώνυμο</label>
                                <input type="text" class="form-control" id="candidate_surname" name="candidate_surname"
                                    placeholder="π.χ. Δημητρίου" required>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="form-footer">

                    <a href="{{ route('home') }}" class="btn btn-light">
                        Άκυρο
                    </a>

                    <button type="submit" class="btn btn-dark">
                        Καταχώρηση
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection

@section('styles')
    <style>
        .new-timeline-page {
            max-width: 750px;
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
            color: white;
            border-radius: 12px;
        }

        .form-header-text {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: 1.5px;
            font-weight: 800;
            color: #9ca3af;
            text-transform: uppercase;
        }

        .form-header p {
            margin: 0;
            color: #6b7280;
            font-size: 15px;
            font-weight: 500;
        }

        /* FORM BODY */
        .form-body {
            padding: 35px 30px;
        }

        .section-title {
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #374151;
        }

        .candidate-section {
            margin-top: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        /* INPUTS */
        .form-control {
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

        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .form-control:focus {
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

        /* BUTTONS */
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

        /* RESPONSIVE */
        @media (max-width: 576px) {
            .new-timeline-page {
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

            .form-header p {
                font-size: 13px;
            }

            .form-body {
                padding: 25px 20px;
            }

            .candidate-section {
                margin-top: 30px;
            }

            .form-footer {
                padding: 20px;
            }
        }
    </style>
@endsection
