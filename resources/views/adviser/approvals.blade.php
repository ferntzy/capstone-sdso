@php $container = 'container-xxl'; @endphp
@extends('layouts/contentNavbarLayout')
@section('title', 'Pending Permit Reviews')

@section('content')
  <div class="{{ $container }} py-4">
    <h4 class="fw-bold mb-4">Pending Permit Reviews</h4>

    <div class="row g-3">
      @forelse($pendingPermits as $permitFlow)
        @php
          $permit = $permitFlow->permit;
          // Approval stages and approvals collection for design (optional)
          $stages = ['Faculty_Adviser', 'BARGO', 'SDSO_Head', 'SAS_Director', 'VP_SAS'];
          $approvals = $permit->eventApprovalFlows ?? collect();
        @endphp

        <div class="col-12 col-md-6 col-xl-4">
          <div class="card mb-3 shadow-sm rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
              <div class="fs-6 fw-bold">{{ $permit->title_activity }}</div>
              <small class="badge bg-warning text-dark">{{ $permitFlow->created_at->format('M d, Y') }}</small>
            </div>

            <div class="card-body">
              <p class="text-muted mb-1">{{ $permit->organization->organization_name ?? 'Unknown Organization' }}</p>
              <p class="mb-1"><strong>Purpose:</strong> {{ Str::limit($permit->purpose ?? 'N/A', 160) }}</p>
              <p class="mb-1"><strong>Venue:</strong> {{ $permit->venue ?? 'N/A' }}</p>
              <p class="mb-2"><strong>Date:</strong>
                {{ \Carbon\Carbon::parse($permit->date_start)->format('M d, Y') }}
                @if ($permit->date_end)
                  - {{ \Carbon\Carbon::parse($permit->date_end)->format('M d, Y') }}
                @endif
              </p>

              {{-- Buttons --}}
              <div class="d-flex gap-2 flex-wrap">
                {{-- View PDF (opens modal) --}}
                @if($permit->pdf_data)
                  <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                    data-bs-target="#pdfModal_{{ $permit->hashed_id }}">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> View PDF
                  </button>

                @else
                  <span class="text-muted small">PDF not available</span>
                @endif

                {{-- Open Approve modal --}}
                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                  data-bs-target="#approveModal_{{ $permitFlow->approval_id }}">
                  <i class="bi bi-check-circle me-1"></i> Approve
                </button>

                {{-- Reject modal --}}
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                  data-bs-target="#rejectModal_{{ $permitFlow->approval_id }}">
                  <i class="bi bi-x-circle me-1"></i> Reject
                </button>
              </div>

              {{-- Small approval progress (visual) --}}
              <div class="mt-3">
                <small class="text-muted">Approval stages</small>
                <div class="d-flex gap-2 mt-2">
                  @foreach($stages as $stage)
                    @php
                      $ap = $approvals->firstWhere('approver_role', $stage);
                      $st = $ap->status ?? 'pending';
                      $badge = $st === 'approved' ? 'bg-success' : ($st === 'rejected' ? 'bg-danger' : 'bg-secondary');
                    @endphp
                    <span class="badge {{ $badge }} text-white">{{ str_replace('_', ' ', $stage) }}</span>
                  @endforeach
                </div>
              </div>

            </div>
          </div>
        </div>

        {{-- Reject Modal --}}
        <div class="modal fade" id="rejectModal_{{ $permitFlow->approval_id }}" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form action="{{ route('faculty.reject', $permitFlow->approval_id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                  <h5 class="modal-title">Reject Permit</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <label class="form-label">Reason / Comments</label>
                  <textarea class="form-control" name="comments" rows="4" required></textarea>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-danger">Confirm Reject</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        {{-- Approve Modal (password + optional signature upload) --}}
        <div class="modal fade" id="approveModal_{{ $permitFlow->approval_id }}" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form action="{{ route('faculty.approve', $permitFlow->approval_id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                  <h5 class="modal-title">Approve Permit</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <p class="mb-2">You are approving: <strong>{{ $permit->title_activity }}</strong></p>

                  <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Upload Signature (optional)</label>
                    <input type="file" name="signature_upload" class="form-control" accept="image/*">
                    <small class="text-muted">If you don't upload a signature, the approval will still be recorded.</small>
                  </div>

                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Confirm Approve</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        {{-- PDF Modal --}}
        @if($permit->pdf_data)
          <div class="modal fade" id="pdfModal_{{ $permit->hashed_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
              <div class="modal-content rounded-3">
                <div class="modal-header bg-primary text-white rounded-top-3">
                  <h5 class="modal-title">Permit: {{ $permit->title_activity }}</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="height: 80vh;">
                  <iframe src="{{ route('faculty.permit.view', ['hashed_id' => $permit->hashed_id]) }}"
                    style="width:100%; height:100%; border:none;"></iframe>
                </div>
                <div class="modal-footer">
                  <a href="{{ route('faculty.permit.view', ['hashed_id' => $permit->hashed_id]) }}" target="_blank"
                    class="btn btn-success d-flex align-items-center">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Open in New Tab
                  </a>
                  <button class="btn btn-danger d-flex align-items-center" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle-fill me-1"></i> Close
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endif


      @empty
        <div class="col-12">
          <div class="alert alert-info text-center">
            No pending approvals.
          </div>
        </div>
      @endforelse
    </div>
  </div>
@endsection