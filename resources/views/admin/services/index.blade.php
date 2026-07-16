@extends('layouts.admin')

@section('title', 'Kelola Layanan | MediLabs Admin')

@section('content')
    <section class="admin-section">
        <x-page-header
            title="Kelola Layanan"
            description="Admin dapat menambah, mengubah, dan mengaktifkan atau menonaktifkan layanan laboratorium"
            wrapper-class="admin-heading"
        />

        <div class="admin-action-row">
            <a class="button admin-button" href="{{ route('admin.services.create') }}">Tambah Layanan</a>
        </div>

        <form class="dark-panel admin-search-card manage-search-card" action="{{ route('admin.services.index') }}" method="GET">
            <label>
                <span>Cari Layanan</span>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Nama layanan atau slug">
            </label>

            <label style="margin-top: 12px;">
                <span>Status</span>
                <select name="status">
                    <option value="">Semua</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </label>

            <button class="button admin-button" type="submit">Filter</button>
        </form>

        <div class="table-card admin-table-card manage-table-card">
            <table class="med-table manage-table">
                <thead>
                    <tr>
                        <th>Visual</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td>
                                <div class="admin-service-thumb">
                                    <x-service-visual
                                        :service="$service"
                                        variant="admin"
                                    />
                                </div>
                            </td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->slug }}</td>
                            <td>Rp {{ number_format((float) $service->price, 0, ',', '.') }}</td>
                            <td><x-status-badge :status="$service->status" /></td>
                            <td>
                                <div class="admin-action-row">
                                    <a class="button admin-button" href="{{ route('admin.services.edit', $service) }}">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.services.toggle-status', $service) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="button admin-button" type="submit">
                                            {{ $service->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $services->links() }}
    </section>
@endsection