@extends('layouts.admin')

@section('title', $formMode === 'create' ? 'Tambah Layanan | MediLabs Admin' : 'Edit Layanan | MediLabs Admin')

@section('content')
    <section class="admin-section">
        <x-page-header
            :title="$formMode === 'create' ? 'Tambah Layanan' : 'Edit Layanan'"
            description="Lengkapi data layanan laboratorium yang akan ditampilkan di frontend"
            wrapper-class="admin-heading"
        />

        <form
            class="dark-panel admin-search-card"
            action="{{ $formMode === 'create' ? route('admin.services.store') : route('admin.services.update', $service) }}"
            method="POST"
            style="width: min(760px, 100%);"
        >
            @csrf
            @if ($formMode === 'edit')
                @method('PUT')
            @endif

            <div class="admin-service-image-note" role="note">
                <i class="bi bi-image" aria-hidden="true"></i>
                <span>
                    Layanan yang belum memiliki ilustrasi khusus akan memakai
                    visual default generik pada halaman pasien. Upload gambar belum
                    tersedia karena memerlukan field database dan penyimpanan file.
                </span>
            </div>

            <label>
                <span>Nama Layanan</span>
                <input type="text" name="name" value="{{ old('name', $service->name) }}" required>
            </label>

            <label style="margin-top: 14px;">
                <span>Slug</span>
                <input type="text" name="slug" value="{{ old('slug', $service->slug) }}" placeholder="opsional, akan dibuat otomatis jika kosong">
            </label>

            <label style="margin-top: 14px;">
                <span>Harga</span>
                <input type="number" name="price" value="{{ old('price', $service->price) }}" min="0" required>
            </label>

            <label style="margin-top: 14px;">
                <span>Status</span>
                <select name="status" required>
                    <option value="active" @selected(old('status', $service->status ?: 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $service->status) === 'inactive')>Inactive</option>
                </select>
            </label>

            <label style="margin-top: 14px;">
                <span>Deskripsi</span>
                <textarea name="description" rows="4">{{ old('description', $service->description) }}</textarea>
            </label>

            <label style="margin-top: 14px;">
                <span>Benefit</span>
                <textarea name="benefit" rows="4">{{ old('benefit', $service->benefit) }}</textarea>
            </label>

            <label style="margin-top: 14px;">
                <span>Persiapan</span>
                <textarea name="preparation" rows="4">{{ old('preparation', $service->preparation) }}</textarea>
            </label>

            <div class="admin-action-row" style="margin-top: 20px;">
                <button class="button admin-button" type="submit">
                    {{ $formMode === 'create' ? 'Simpan Layanan' : 'Update Layanan' }}
                </button>

                <a class="button admin-button" href="{{ route('admin.services.index') }}">
                    Kembali
                </a>
            </div>
        </form>
    </section>
@endsection