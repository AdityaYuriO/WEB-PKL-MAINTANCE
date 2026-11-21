@extends('layout.main')
@section('content')

<div class="container-fluid">
    <h5 class="mb-3">Tujuan Pembelajaran untuk INSTITUSI: <b>{{ $iduka->name }}</b></h5>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Capaian Pembelajaran</th>
                        <th>Nama Tujuan Pembelajaran</th>
                        <th>Checklist</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cp_atps as $cp)
                        <tr>
                            <td><b>{{ $cp->cp }}</b></td>
                            <td colspan="2"></td>
                        </tr>
                        @foreach ($cp->atps as $atp)
                            <tr>
                                <td></td>
                                <td>{{ $atp->atp }}</td>
                                <td>
                                    <input type="checkbox" class="tp-check" name="tp_check[]" 
                                           value="{{ $atp->id }}" 
                                           {{ in_array($atp->id, $selected_atps) ? 'checked' : '' }} disabled>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
