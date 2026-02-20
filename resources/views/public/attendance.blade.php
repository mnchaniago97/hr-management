@extends('layouts.fullscreen-layout')

@section('content')
<div class="flex min-h-screen items-center justify-center px-4 py-10">
  <div class="w-full max-w-2xl">
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-white/[0.03] sm:p-8">
      <div class="mb-6 text-center">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Absensi Anggota</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Silakan pilih nama lalu lakukan check-in atau check-out.</p>
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

      @if ($errors->any())
      <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
        <ul class="list-disc space-y-1 pl-4">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form method="POST" class="space-y-6" x-data="{ q: '' }">
        @csrf
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Anggota</label>
          <input
            type="text"
            placeholder="Cari nama anggota..."
            class="mb-2 w-full"
            x-model="q"
          >
          <select name="member_id" required>
            <option value="">Pilih anggota</option>
            @foreach($members ?? [] as $member)
              <option
                value="{{ $member->id }}"
                x-show="{{ json_encode($member->name) }}.toLowerCase().includes(q.toLowerCase())"
                {{ old('member_id') == $member->id ? 'selected' : '' }}
              >
                {{ $member->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
          <button type="submit" formaction="{{ route('attendance.public.checkin') }}" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Check In
          </button>
          <button type="submit" formaction="{{ route('attendance.public.checkout') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Check Out
          </button>
        </div>
      </form>

      <div class="mt-6 rounded-lg border border-gray-100 bg-gray-50 p-4 text-xs text-gray-500 dark:border-gray-800 dark:bg-white/[0.02] dark:text-gray-400">
        Catatan: Check-in hanya bisa sekali per hari. Check-out hanya bisa dilakukan setelah check-in.
      </div>
    </div>
  </div>
</div>
@endsection
