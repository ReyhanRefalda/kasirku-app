@extends('layouts.main')

@section('title', 'Log Aktivitas')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Log Aktivitas</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Riwayat Aktivitas</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Deskripsi</th>
                                    <th>Model</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                           
                            <tbody>
                                @forelse($logs as $log)
                                
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                           
                                            <strong>{{ ucfirst($log->formatted_description) }}</strong> <br>

                                            @if ($log->event === 'created')
                                                <ul class="mt-2">
                                                    @foreach ($log->properties['attributes'] as $key => $newValue)
                                                        <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                            "{{ $newValue }}"</li>
                                                    @endforeach
                                                </ul>
                                            @elseif($log->event === 'updated' && $log->properties->has('old'))
                                                <ul class="mt-2">
                                                    @foreach ($log->properties['attributes'] as $key => $newValue)
                                                        @if (isset($log->properties['old'][$key]) && $log->properties['old'][$key] !== $newValue)
                                                            <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                                "{{ $log->properties['old'][$key] }}" →
                                                                "{{ $newValue }}"
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @elseif($log->event === 'deleted' && $log->properties->has('old'))
                                                <ul class="mt-2">
                                                    @foreach ($log->properties['old'] as $key => $oldValue)
                                                        <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                            "{{ $oldValue }}"</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>{{ ucfirst($log->log_name) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}</td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada aktivitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>



                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <nav class="d-inline-block" id="pagination-links">
                            {{ $logs->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
