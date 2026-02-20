@extends('layouts.fullscreen-layout')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#f4f6fb] text-gray-900">
  <div class="pointer-events-none absolute -left-28 -top-28 h-72 w-72 rounded-full bg-[#c7b9ff]/40 blur-3xl"></div>
  <div class="pointer-events-none absolute -right-24 top-20 h-80 w-80 rounded-full bg-blue-200/40 blur-3xl"></div>

  <div class="mx-auto flex min-h-screen w-full max-w-5xl flex-col px-6 py-10">
    <div class="mb-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="/images/logo/logoksr.png" alt="KSR PMI UNP" class="h-9 w-auto" />
        <div>
          <p class="text-sm font-semibold tracking-wide">KSR PMI UNP</p>
          <p class="text-xs text-gray-500">Loyalitas untuk kemanusiaan</p>
        </div>
      </div>
      <a href="{{ route('public.home') }}"
        class="rounded-full border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:border-gray-400 hover:text-gray-900">
        Beranda
      </a>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
      <div class="rounded-[28px] border border-white/70 bg-white/90 p-6 shadow-sm backdrop-blur sm:p-8">
        <div class="mb-6">
          <span class="inline-flex items-center rounded-full bg-[#c7b9ff] px-3 py-1 text-xs font-semibold text-[#1f2140]">
            Form Publik
          </span>
          <h1 class="mt-3 text-2xl font-semibold text-gray-800">Absensi Anggota</h1>
          <p class="mt-1 text-sm text-gray-500">Silakan pilih nama lalu lakukan check-in atau check-out.</p>
        </div>

        @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
          {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
          {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
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
            <label class="mb-2 block text-sm font-medium text-gray-700">Nama Anggota</label>
            <input
              type="text"
              placeholder="Cari nama anggota..."
              class="mb-2 w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60"
              x-model="q"
            >
            <select name="member_id" required
              class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
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
            <button type="submit" formaction="{{ route('attendance.public.checkin') }}"
              class="inline-flex items-center justify-center rounded-full bg-[#2f64f6] px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-[#2554d4]">
              Check In
            </button>
            <button type="submit" formaction="{{ route('attendance.public.checkout') }}"
              class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:border-gray-400">
              Check Out
            </button>
          </div>
        </form>

        <div class="mt-6 rounded-2xl border border-white/70 bg-gray-50/70 p-4 text-xs text-gray-500">
          Catatan: Check-in hanya bisa sekali per hari. Check-out hanya bisa dilakukan setelah check-in.
        </div>
      </div>

      <div class="rounded-[28px] bg-[#c7b9ff] p-6 text-[#1f2140] shadow-sm">
        <h2 class="text-xl font-semibold">Panduan Singkat</h2>
        <p class="mt-2 text-sm text-[#2f3166]">
          Pastikan memilih nama anggota yang tepat sebelum menyimpan absensi.
        </p>
        <div class="mt-6 grid gap-3 text-sm">
          <div class="rounded-2xl bg-white/80 p-4">
            <p class="text-xs text-[#6a6fa7]">Check-in</p>
            <p class="mt-1 font-semibold">Mulai kegiatan harian</p>
          </div>
          <div class="rounded-2xl bg-white/80 p-4">
            <p class="text-xs text-[#6a6fa7]">Check-out</p>
            <p class="mt-1 font-semibold">Selesaikan kegiatan</p>
          </div>
          <div class="rounded-2xl bg-white/80 p-4">
            <p class="text-xs text-[#6a6fa7]">Validasi</p>
            <p class="mt-1 font-semibold">Admin memeriksa data</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
