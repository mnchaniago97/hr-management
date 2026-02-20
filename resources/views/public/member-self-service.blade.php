@extends('layouts.app')

@section('title', 'Kelola Data Mandiri')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-[28px] border border-white/70 bg-white/90 px-6 py-8 shadow-sm backdrop-blur dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
        <div>
          <span class="inline-flex items-center rounded-full bg-[#c7b9ff] px-3 py-1 text-xs font-semibold text-[#1f2140]">
            Self Service
          </span>
          <h2 class="mt-3 text-2xl font-semibold text-black dark:text-white">Kelola Data Anggota Mandiri</h2>
          <p class="mt-2 text-sm text-gray-500">Cari data dengan NIA dan perbarui informasi Anda sendiri.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
          Wajib login
        </span>
      </div>

      @if (session('success'))
        <div class="mb-4 rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
          {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-200">
          {{ session('error') }}
        </div>
      @endif

      @if ($error)
        <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-200">
          {{ $error }}
        </div>
      @endif

      @if ($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-200">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $message)
              <li>{{ $message }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="mb-8" method="GET" action="{{ route('member.self-service') }}">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-[1fr_auto]">
          <div>
            <label for="nia" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Cari NIA</label>
            <input
              type="text"
              id="nia"
              name="nia"
              value="{{ old('nia', $nia) }}"
              placeholder="Contoh: 20240001"
              class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-black shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60 dark:border-gray-600 dark:bg-boxdark dark:text-white"
              required
            >
            <p class="mt-2 text-xs text-gray-500">NIA harus sama dengan NIA akun Anda.</p>
          </div>
          <div class="flex items-end">
            <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-[#2f64f6] px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-[#2554d4] md:w-auto">
              Cari Data
            </button>
          </div>
        </div>
      </form>

      @if ($member)
        <div class="rounded-[22px] border border-white/70 bg-white/90 p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Data Anggota</h3>
          <form method="POST" action="{{ route('member.self-service.update', $member->id) }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf

            <div>
              <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap</label>
              <input
                type="text"
                name="name"
                value="{{ old('name', $member->name) }}"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-black shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60 dark:border-gray-600 dark:bg-boxdark dark:text-white"
                required
              >
            </div>

            <div>
              <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Telepon</label>
              <input
                type="text"
                name="phone"
                value="{{ old('phone', $member->phone) }}"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-black shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60 dark:border-gray-600 dark:bg-boxdark dark:text-white"
              >
            </div>

            <div class="md:col-span-2">
              <button type="submit" class="inline-flex items-center rounded-full bg-[#2f64f6] px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-[#2554d4]">
                Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
