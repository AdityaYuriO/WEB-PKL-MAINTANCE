@extends('layout.main')

@section('title', 'Laporan Absensi')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Absensi</h3>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <form id="filterForm" class="form-row">
                        <div class="form-group col-md-3">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="end_date">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        
                        @if(in_array(auth()->user()->role, ['hubin', 'kepsek', 'kaprog']))
                        <div class="form-group col-md-3">
                            <label for="pembimbing_id">Pembimbing</label>
                            <select class="form-control" id="pembimbing_id" name="pembimbing_id">
                                <option value="">Semua Pembimbing</option>
                                @foreach($pembimbings as $pembimbing)
                                <option value="{{ $pembimbing->id }}">{{ $pembimbing->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <div class="form-group col-md-3">
                            <label for="iduka_id">IDUKA</label>
                            <select class="form-control" id="iduka_id" name="iduka_id">
                                <option value="">Semua IDUKA</option>
                                @foreach($idukas as $iduka)
                                <option value="{{ $iduka->id }}">{{ $iduka->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="tepat_waktu">Tepat Waktu</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="izin">Izin</option>
                                <option value="dinas">Dinas Luar</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                        
                        <!-- Hidden fields for pagination -->
                        <input type="hidden" id="page" name="page" value="1">
                        <input type="hidden" id="per_page" name="per_page" value="20">
                    </form>
                </div>
            </div>
            
            <!-- Tabel Laporan -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="laporanTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Pembimbing</th>
                            <th>IDUKA</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi via AJAX -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <span class="text-muted">Menampilkan <span id="from">0</span> - <span id="to">0</span> dari <span id="totalData">0</span> record</span>
                </div>
                <div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="pagination">
                            <!-- Pagination items akan di-generate oleh JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
 $(document).ready(function() {
    // Set tanggal default (30 hari terakhir)
    var today = new Date();
    var thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    $('#start_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    }).datepicker('setDate', thirtyDaysAgo);
    
    $('#end_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    }).datepicker('setDate', today);
    
    // Submit form filter
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        // Reset ke halaman pertama saat filter berubah
        $('#page').val(1);
        loadLaporan();
    });
    
    // Load data awal
    loadLaporan();
    
    function loadLaporan() {
        $('#laporanTable tbody').html('<tr><td colspan="9" class="text-center">Loading...</td></tr>');
        
        $.ajax({
            url: '{{ route("laporan.absensi.data") }}',
            type: 'POST',
            data: $('#filterForm').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var html = '';
                if (response.data.length > 0) {
                    $.each(response.data, function(i, item) {
                        html += '<tr>';
                        html += '<td>' + item.tanggal + '</td>';
                        html += '<td>' + item.nis + '</td>';
                        html += '<td>' + item.nama_siswa + '</td>';
                        html += '<td>' + item.pembimbing + '</td>';
                        html += '<td>' + item.iduka + '</td>';
                        html += '<td>' + item.jam_masuk + '</td>';
                        html += '<td>' + item.jam_pulang + '</td>';
                        html += '<td>' + item.status + '</td>';
                        html += '<td>' + item.keterangan + '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="9" class="text-center">Tidak ada data</td></tr>';
                }
                
                $('#laporanTable tbody').html(html);
                
                // Update info pagination
                $('#from').text(response.pagination.from);
                $('#to').text(response.pagination.to);
                $('#totalData').text(response.pagination.total);
                
                // Generate pagination
                generatePagination(response.pagination);
            },
            error: function(xhr) {
                var errorMsg = 'Terjadi kesalahan saat memuat data';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                $('#laporanTable tbody').html('<tr><td colspan="9" class="text-center text-danger">' + errorMsg + '</td></tr>');
                $('#totalData').text('0');
                $('#pagination').html('');
            }
        });
    }
    
    function generatePagination(pagination) {
        var html = '';
        var currentPage = pagination.current_page;
        var lastPage = pagination.last_page;
        
        // Previous button
        if (currentPage > 1) {
            html += '<li class="page-item"><a class="page-link" href="#" data-page="' + (currentPage - 1) + '">Previous</a></li>';
        } else {
            html += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }
        
        // Page numbers
        // Tampilkan maksimal 5 halaman
        var startPage = Math.max(1, currentPage - 2);
        var endPage = Math.min(lastPage, startPage + 4);
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }
        
        if (startPage > 1) {
            html += '<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>';
            if (startPage > 2) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        for (var i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                html += '<li class="page-item active"><span class="page-link">' + i + '</span></li>';
            } else {
                html += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
            }
        }
        
        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            html += '<li class="page-item"><a class="page-link" href="#" data-page="' + lastPage + '">' + lastPage + '</a></li>';
        }
        
        // Next button
        if (currentPage < lastPage) {
            html += '<li class="page-item"><a class="page-link" href="#" data-page="' + (currentPage + 1) + '">Next</a></li>';
        } else {
            html += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }
        
        $('#pagination').html(html);
        
        // Tambahkan event listener untuk pagination
        $('#pagination .page-link').on('click', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            if (page) {
                $('#page').val(page);
                loadLaporan();
            }
        });
    }
});
</script>
@endpush