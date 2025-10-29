@php
  $container = 'container-xxl';
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'User Logs')

@section('content')
  <div class="{{ $container }} py-4">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Activity Logs</h5>
      </div>

      <div class="card-body">
        @if ($logs->isEmpty())
          <div class="alert alert-info text-center">No user logs found.</div>
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-primary text-center">
                <tr>
                  <th>#</th>
                  <th>Username</th>
                  <th>Action</th>
                  <th>IP Address</th>
                  <th>User Agent</th> <!-- 👈 add this -->
                  <th>Date & Time</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($logs as $index => $log)
                  <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                      @if ($log->user)
                        {{ $log->user->username }}
                      @else
                        <span class="text-muted fst-italic">Unknown User (ID: {{ $log->user_id }})</span>
                      @endif
                    </td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                    <td style="max-width: 250px; word-wrap: break-word;">{{ $log->user_agent ?? 'N/A' }}</td>
                    <!-- 👈 display -->
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                  </tr>
                @endforeach
              </tbody>

            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection