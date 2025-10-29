@php
  $container = 'container-xxl';
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Permit Tracking')

@section('content')
  <div class="{{ $container }} py-4">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Permit Tracking</h5>
      </div>

      <div class="card-body">
        @if ($permits->isEmpty())
          <div class="alert alert-info text-center">
            You have not submitted any activity permits yet.
          </div>
        @else
          @foreach ($permits as $permit)
            <div class="card mb-4 border border-light shadow-sm">
              <div class="card-body">
                <h5 class="fw-bold text-primary mb-2">{{ $permit->title_activity }}</h5>
                <p class="mb-2 text-muted">{{ $permit->organization->organization_name ?? 'Unknown Organization' }}</p>
                <p class="mb-2"><strong>Purpose:</strong> {{ $permit->purpose ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Venue:</strong> {{ $permit->venue ?? 'N/A' }}</p>
                <p class="mb-3">
                  <strong>Date:</strong>
                  {{ \Carbon\Carbon::parse($permit->date_start)->format('M d, Y') }}
                  @if ($permit->date_end)
                    - {{ \Carbon\Carbon::parse($permit->date_end)->format('M d, Y') }}
                  @endif
                </p>

                {{-- ✅ Tracking Progress --}}
                <h6 class="fw-semibold text-dark mb-3">Approval Progress</h6>

                @php
                  $stages = ['Faculty_Adviser', 'BARGO', 'SDSO_Head', 'SAS_Director', 'VP_SAS'];
                  $approvals = $permit->event->approvals ?? collect();
                @endphp

                <div class="d-flex justify-content-between flex-wrap" style="gap: 1rem;">
                  @foreach ($stages as $stage)
                    @php
                      $approval = $approvals->firstWhere('approver_role', $stage);
                      $status = $approval->status ?? 'pending';
                      $statusColor = match ($status) {
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        default => 'bg-secondary'
                      };
                    @endphp

                    <div class="text-center flex-fill" style="min-width: 120px;">
                      <div class="position-relative d-inline-block mb-2">
                        <div class="rounded-circle {{ $statusColor }}"
                          style="width: 40px; height: 40px; display: flex; justify-content: center; align-items: center; color: white;">
                          @if ($status === 'approved')
                            <i class="mdi mdi-check-bold fs-5"></i>
                          @elseif ($status === 'rejected')
                            <i class="mdi mdi-close fs-5"></i>
                          @else
                            <i class="mdi mdi-clock-outline fs-5"></i>
                          @endif
                        </div>
                      </div>
                      <div class="small fw-semibold">{{ str_replace('_', ' ', $stage) }}</div>
                      <div class="text-muted small text-capitalize">{{ $status }}</div>
                    </div>
                  @endforeach
                </div>

                @if ($approvals->where('status', 'rejected')->isNotEmpty())
                  <div class="alert alert-danger mt-3 mb-0">
                    <strong>Rejected:</strong> One or more offices have rejected this request.
                  </div>
                @elseif ($approvals->where('status', 'approved')->count() === count($stages))
                  <div class="alert alert-success mt-3 mb-0">
                    ✅ This activity has been fully approved and recorded in the system.
                  </div>
                @else
                  <div class="alert alert-warning mt-3 mb-0">
                    ⏳ This permit is still under review. Please wait for further updates.
                  </div>
                @endif

                <div class="text-end mt-3">
                  <a href="{{ route('student.permit.view', $permit->permit_id) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">
                    <i class="mdi mdi-file-pdf-box"></i> View Permit PDF
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
@endsection