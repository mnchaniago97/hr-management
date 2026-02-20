@extends('layouts.app')

@section('title', 'Edit Sertifikasi')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Edit Sertifikasi</h2>
        <a href="{{ route('hr.certifications.show', $certification->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">Lihat Detail</a>
      </div>

      <form action="{{ route('hr.certifications.update', $certification->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Anggota <span class="text-red-500">*</span></label>
            <select name="member_id" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
              <option value="">Pilih Anggota</option>
              @foreach($members ?? [] as $member)
                <option value="{{ $member->id }}" {{ old('member_id', $certification->member_id) == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Nama Sertifikasi <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $certification->name) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Penyedia <span class="text-red-500">*</span></label>
            <input type="text" name="provider" value="{{ old('provider', $certification->provider) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Tanggal Terbit <span class="text-red-500">*</span></label>
            <input type="date" name="issue_date" value="{{ old('issue_date', $certification->issue_date) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Tanggal Expired</label>
            <input type="date" name="expiry_date" value="{{ old('expiry_date', $certification->expiry_date) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white">
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Status <span class="text-red-500">*</span></label>
            <select name="status" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white" required>
              <option value="active" {{ old('status', $certification->status) == 'active' ? 'selected' : '' }}>Aktif</option>
              <option value="expired" {{ old('status', $certification->status) == 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
              <option value="revoked" {{ old('status', $certification->status) == 'revoked' ? 'selected' : '' }}>Dicabut</option>
            </select>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Credential ID</label>
            <input type="text" name="credential_id" value="{{ old('credential_id', $certification->credential_id) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white">
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Credential URL</label>
            <input type="url" name="credential_url" value="{{ old('credential_url', $certification->credential_url) }}" class="w-full rounded border border-gray-300 bg-white px-4 py-2 dark:border-gray-600 dark:bg-boxdark dark:text-white">
          </div>
        </div>
        <div class="flex justify-end gap-4">
          <a href="{{ route('hr.certifications.show', $certification->id) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50">Batal</a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
