@extends('layouts/commonMaster')

@php
  /* Display elements */
  $contentNavbar = true;
  $containerNav = ($containerNav ?? 'container-xxl');
  $isNavbar = ($isNavbar ?? true);
  $isMenu = ($isMenu ?? true);
  $isFlex = ($isFlex ?? false);
  $isFooter = ($isFooter ?? true);

  /* HTML Classes */
  $navbarDetached = 'navbar-detached';

  /* Content classes */
  $container = ($container ?? 'container-xxl');
@endphp

@section('layoutContent')
  <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
    <div class="layout-container">

      @if ($isMenu)
        @include('layouts/sections/menu/verticalMenu')
      @endif

      <!-- Layout page -->
      <div class="layout-page">
        <!-- BEGIN: Navbar-->
        @if ($isNavbar)
          @include('layouts/sections/navbar/navbar')
        @endif
        <!-- END: Navbar-->

        <!-- Content wrapper -->
        <div class="content-wrapper">

          <!-- Content -->
          @if ($isFlex)
            <div class="{{ $container }} d-flex align-items-stretch flex-grow-1 p-0">
          @else
              <div class="{{ $container }} flex-grow-1 container-p-y">
            @endif

              <div class="row mb-4">
                <div class="col-12 text-center mb-4">
                  <h3 class="">Analytics Dashboard (Placeholder)</h3>
                  <p class="text-muted">This is where your analytics summary will appear.</p>
                </div>

                <!-- Metric Cards -->

                <div class="col-md-3 col-sm-6 mb-4">
                  <div class="card shadow-sm h-100">
                    <a href="/admin/pending-events" class="d-inline-block text-decoration-none">
                      <div class="card-body">
                        <i class="ti ti-calendar-event text-primary" style="font-size: 2.5rem; margin-bottom: 8px;"></i>
                        <h4 id="pendingEvents" class="card-title">0</h4>
                        <p class="card-text text-muted">Pending Events</p>
                      </div>
                    </a>
                  </div>
                </div>


                <div class="col-md-3 col-sm-6 mb-4">
                  <div class="card shadow-sm h-100">
                    <div class="card-body">
                      <i class="ti ti-bolt text-info" style="font-size: 2.5rem; margin-bottom: 8px;"></i>
                      <h4 id="ongoingEvents" class="card-title">0</h4>
                      <p class="card-text text-muted">Ongoing Events</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-4">
                  <div class="card shadow-sm h-100">
                    <div class="card-body">
                      <i class="ti ti-users text-success" style="font-size: 2.5rem; margin-bottom: 8px;"></i>
                      <h4 id="activeUsers" class="card-title">0</h4>
                      <p class="card-text text-muted">Active Users</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-4">
                  <div class="card shadow-sm h-100">
                    <div class="card-body">
                      <i class="ti ti-building text-primary" style="font-size: 2.5rem; margin-bottom: 8px;"></i>
                      <h4 id="activeOrganizations" class="card-title">0</h4>
                      <p class="card-text text-muted">Active Organizations</p>
                    </div>
                  </div>
                </div>
              </div>



              <!-- Calendar -->
              <div class="card shadow-sm mb-4">



              </div>

            </div>
            <!-- / Content -->
            <script src="{{ asset('assets/js/analytics-metrics.js') }}"></script>

            <div class="content-backdrop fade"></div>
          </div>
          <!--/ Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      @if ($isMenu)
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
      @endif

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
@endsection

  {{-- ✅ Include the analytics placeholder script --}}
  @section('page-script')
    {{-- ✅ Include both placeholder scripts here --}}
    <script src="{{ asset('assets/js/analytics-metrics.js') }}"></script>
    <script src="{{ asset('assets/js/analytics.js') }}"></script>
  @endsection