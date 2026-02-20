@extends('layouts.app')

@section('title', 'Ubah User')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Ubah User</h2>
          <p class="text-sm text-gray-500 mt-1">Perbarui data user dan role.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      @if ($errors->any())
      <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
        <ul class="list-disc space-y-1 pl-4">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nama <span class="text-error-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
          </div>

          <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Email <span class="text-error-500">*</span>
            </label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
          </div>

          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Role <span class="text-error-500">*</span>
            </label>
            <select name="role" required>
              @foreach($roles as $roleOption)
                <option value="{{ $roleOption }}" @selected(old('role', $user->role) === $roleOption)>{{ $roleOption }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Password Baru
            </label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah">
          </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
          <a href="{{ route('admin.users.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
