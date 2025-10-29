@php $container = 'container-xxl'; @endphp
@extends('layouts/contentNavbarLayout')
@section('title', 'Faculty Adviser Dashboard')

@section('content')
  <div class="{{ $container }} py-4">
    <h4 class="fw-bold mb-4">Faculty Adviser Dashboard</h4>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center">
            <h5 class="text-primary">Pending Reviews</h5>
            <h2>{{ $pendingReviews ?? 0 }}</h2>
            <p class="text-muted">Permit requests awaiting your approval</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center">
            <h5 class="text-success">Approved</h5>
            <h2>{{ $approved ?? 0 }}</h2>
            <p class="text-muted">Documents you have approved</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center">
            <h5 class="text-danger">Rejected</h5>
            <h2>{{ $rejected ?? 0 }}</h2>
            <p class="text-muted">Documents you have rejected</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection