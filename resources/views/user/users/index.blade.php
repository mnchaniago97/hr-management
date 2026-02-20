@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Kelola User</h2>
          <p class="text-sm text-gray-500 mt-1">Hanya Super Admin yang dapat mengelola user.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
          Tambah User
        </a>
      </div>

      @if (session('success'))
      <div class="mb-4 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-400">
        {{ session('success') }}
      </div>
      @endif

      @if (session('error'))
      <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
        {{ session('error') }}
      </div>
      @endif

      <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
          <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pencarian</label>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau email...">
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Role</label>
            <select name="role">
              <option value="">Semua Role</option>
              @foreach($roles as $roleOption)
                <option value="{{ $roleOption }}" @selected(($role ?? '') === $roleOption)>{{ $roleOption }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
          <a href="{{ route('admin.users.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Reset
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Cari</button>
        </div>
      </form>

      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
          <table class="w-full min-w-[900px]">
            <thead>
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p></th>
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama</p></th>
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Email</p></th>
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Role</p></th>
                <th class="px-5 py-3 text-center sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p></th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $index => $user)
              <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ ($users->firstItem() ?? 1) + $index }}</td>
                <td class="px-5 py-4 text-theme-sm font-medium text-gray-800 dark:text-white/90 sm:px-6">{{ $user->name }}</td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $user->email }}</td>
                <td class="px-5 py-4 text-theme-sm sm:px-6">
                  <span class="inline-flex rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                    {{ $user->role }}
                  </span>
                </td>
                <td class="px-5 py-4 text-center sm:px-6">
                  <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800">
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus user ini?')">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <td colspan="5" class="px-5 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">Data tidak tersedia.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($users->hasPages())
      <div class="mt-4 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data</p>
        {{ $users->appends(request()->query())->links('pagination::tailwind') }}
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
