@php $container = 'container-xxl'; @endphp
@extends('layouts/contentNavbarLayout')
@section('title', 'BARGO Dashboard')

@section('content')
  <div class="{{ $container }} py-4">
    <h4 class="fw-bold mb-4">Barangay Government Dashboard</h4>

    <div class="alert alert-info">Pending approvals forwarded from Faculty Adviser</div>

    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="mb-3">Documents for Review</h5>
        @if(isset($documents) && $documents->isNotEmpty())
          <ul class="list-group">
            @foreach($documents as $doc)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $doc->event_title }}
                <a href="{{ route('bargo.review', $doc->id) }}" class="btn btn-sm btn-primary">Review</a>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-muted">No documents to review.</p>
        @endif
      </div>
    </div>
  </div>
@endsection