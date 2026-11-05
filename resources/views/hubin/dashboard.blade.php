@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  {{-- Welcome Card --}}
  <div class="row">
    <div class="col-lg-12 mb-4 order-0">
      <div class="card shadow-sm border-0 bg-primary text-white card-hover">
        <div class="d-flex align-items-end row">
          <div class="col-sm-8">
            <div class="card-body">
              <h4 class="card-title text-white mb-2 fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h4>
              <p class="mb-4 text-white-50">
                Ini adalah dashboard panel Hubin. Anda dapat memantau statistik dan rekapitulasi data usulan PKL siswa secara keseluruhan.
              </p>
            </div>
          </div>
          <div class="col-sm-4 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img src="{{ asset('snet/assets/img/illustrations/man-with-laptop-light.png') }}" 
                   height="140" 
                   alt="Welcome Hubin" 
                   class="d-none d-sm-block ms-auto">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Cards --}}
  <div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card shadow-sm border-0 h-100 card-hover">
        <div class="card-body d-flex align-items-center">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="fas fa-lightbulb fs-4"></i>
            </span>
          </div>
          <div>
            <h6 class="text-muted mb-0">Total Penempatan Siswa</h6>
            <h4 class="mb-0 fw-bold text-dark mt-1">{{ $jumlahUsulan }}</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card shadow-sm border-0 h-100 card-hover">
        <div class="card-body d-flex align-items-center">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success">
              <i class="fas fa-check-circle fs-4"></i>
            </span>
          </div>
          <div>
            <h6 class="text-muted mb-0">Jumlah Diterima</h6>
            <h4 class="mb-0 fw-bold text-dark mt-1">{{ $jumlahDiterima }}</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-12 mb-4">
      <div class="card shadow-sm border-0 h-100 card-hover">
        <div class="card-body d-flex align-items-center">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="fas fa-times-circle fs-4"></i>
            </span>
          </div>
          <div>
            <h6 class="text-muted mb-0">Jumlah Ditolak</h6>
            <h4 class="mb-0 fw-bold text-dark mt-1">{{ $jumlahDitolak }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Chart --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow-sm border-0 card-hover">
        <div class="card-body">
          <h5 class="card-title text-center mb-3">Statistik Usulan</h5>
          <div class="table-responsive">
            <p class="text-center text-muted mb-3">Grafik ini menggambarkan jumlah usulan, diterima, dan ditolak.</p>
          </div>
          <div class="chart-container" style="min-height: 250px;">
            <canvas id="statistikChart" height="100"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- SweetAlert --}}
@if(session('success'))
<script>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: "{{ session('success') }}",
    showConfirmButton: false,
    timer: 2000,
    customClass: {
      popup: 'animate__animated animate__fadeInDown'
    }
  });
</script>
@endif

@if(session('error'))
<script>
  Swal.fire({
    icon: 'error',
    title: 'Oops!',
    text: "{{ session('error') }}",
    showConfirmButton: false,
    timer: 2500,
    customClass: {
      popup: 'animate__animated animate__shakeX'
    }
  });
</script>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statistikChart').getContext('2d');
    const statistikChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Usulan', 'Diterima', 'Ditolak'],
        datasets: [{
          label: 'Jumlah',
          data: [{{ $jumlahUsulan }}, {{ $jumlahDiterima }}, {{ $jumlahDitolak }}],
          backgroundColor: [
            'rgba(102, 126, 234, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(255, 99, 132, 0.8)'
          ],
          borderRadius: 10,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 10
            }
          }
        }
      }
    });
  });
</script>



