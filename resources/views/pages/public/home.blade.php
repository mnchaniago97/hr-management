@extends('layouts.fullscreen-layout')

@section('content')
        <header class="relative z-20">
            <div class="mx-auto w-full max-w-6xl px-6 pb-6 pt-8">
                <div class="flex flex-nowrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="/images/logo/logoksr.png" alt="KSR PMI UNP" class="h-9 w-auto" />
                        <div>
                            <p class="text-sm font-semibold tracking-wide">KSR PMI UNP</p>
                            <p class="text-xs text-gray-500">Loyalitas untuk kemanusiaan</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        @auth
                            @php
                                $homeAvatar = null;
                                if (auth()->user()?->avatar) {
                                    $avatarPath = \Illuminate\Support\Str::startsWith(auth()->user()->avatar, ['avatars/', '/'])
                                        ? ltrim(auth()->user()->avatar, '/')
                                        : 'avatars/' . auth()->user()->avatar;
                                    $homeAvatar = \Illuminate\Support\Facades\Storage::disk('public')->url($avatarPath);
                                }
                            @endphp
                            <div class="relative" x-data="{ open: false }">
                                <button type="button" @click="open = !open"
                                    class="flex h-9 items-center gap-2 rounded-full border border-gray-200 bg-white px-3 text-sm hover:border-gray-300">
                                    @if($homeAvatar)
                                        <img src="{{ $homeAvatar }}" alt="{{ auth()->user()->name }}" class="h-7 w-7 rounded-full object-cover" />
                                    @else
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#c7b9ff] text-xs font-semibold text-[#1f2140]">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                        </span>
                                    @endif
                                    <span class="max-w-[120px] truncate font-medium text-gray-700">{{ auth()->user()->name }}</span>
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-transition
                                    class="absolute right-0 mt-3 w-48 rounded-xl border border-gray-100 bg-white py-2 shadow-lg z-50">
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        Dashboard
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="w-full rounded-full border border-gray-300 px-4 py-2 text-center text-sm font-medium text-gray-700 hover:border-gray-400 hover:text-gray-900 sm:w-auto">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="w-full rounded-full bg-[#2f64f6] px-4 py-2 text-center text-sm font-medium text-white shadow-sm hover:bg-[#2554d4] sm:w-auto">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="relative z-0 mx-auto w-full max-w-6xl px-6 pb-16">
            <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="rounded-[32px] bg-white/80 p-6 shadow-sm backdrop-blur">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Beranda</p>
                            <h1 class="mt-2 text-3xl font-semibold text-gray-900 sm:text-4xl">
                                Daily layanan untuk anggota KSR.
                            </h1>
                            <p class="mt-3 text-sm text-gray-600">
                                Mulai dari absensi, inventaris, hingga update data mandiri. Semua shortcut utama ada di sini.
                            </p>
                        </div>
                        <div class="hidden rounded-3xl bg-[#5a4cfe]/10 px-4 py-2 text-xs font-semibold text-[#5a4cfe] sm:block">
                            Sistem Terpadu
                        </div>
                    </div>
                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('attendance.public') }}"
                            class="flex items-center justify-center rounded-full bg-[#111827] px-5 py-2.5 text-sm font-medium text-white hover:bg-black">
                            Absen Anggota
                        </a>
                        <a href="{{ route('asset.public.loan') }}"
                            class="flex items-center justify-center rounded-full border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:border-gray-400">
                            Peminjaman Inventaris
                        </a>
                        <a href="{{ route('health.public.form') }}"
                            class="flex items-center justify-center rounded-full border border-[#c7b9ff] bg-white px-5 py-2.5 text-sm font-medium text-[#2f64f6] hover:border-[#5a4cfe] sm:col-span-2">
                            Layanan Tim Kesehatan
                        </a>
                    </div>
                    <div class="mt-6 flex items-center gap-3 text-xs text-gray-500">
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-emerald-700">
                            Live
                        </span>
                        Shortcut publik tersedia 24/7 untuk anggota.
                    </div>
                </div>
                <div class="rounded-[32px] bg-[#c7b9ff] p-6 text-[#1f2140] shadow-sm">
                    <h2 class="text-xl font-semibold">Panel Cepat</h2>
                    <p class="mt-2 text-sm text-[#2f3166]">
                        Akses layanan favorit dengan sekali klik. Lanjutkan ke dashboard jika membutuhkan modul lengkap.
                    </p>
                    <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-2xl bg-white/80 p-4">
                            <p class="text-xs text-[#6a6fa7]">Absensi</p>
                            <p class="mt-1 font-semibold">Check-in cepat</p>
                        </div>
                        <div class="rounded-2xl bg-white/80 p-4">
                            <p class="text-xs text-[#6a6fa7]">Inventaris</p>
                            <p class="mt-1 font-semibold">Status aset</p>
                        </div>
                        <div class="rounded-2xl bg-white/80 p-4">
                            <p class="text-xs text-[#6a6fa7]">Self Service</p>
                            <p class="mt-1 font-semibold">Update data</p>
                        </div>
                        <div class="rounded-2xl bg-white/80 p-4">
                            <p class="text-xs text-[#6a6fa7]">Rekrutmen</p>
                            <p class="mt-1 font-semibold">Calon anggota</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-10">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Shortcut Layanan</h2>
                        <p class="text-sm text-gray-600">Pilih layanan yang paling sering kamu butuhkan.</p>
                    </div>
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-[#2f64f6] hover:text-[#2554d4]">
                        Masuk Dashboard
                    </a>
                </div>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ route('attendance.public') }}"
                        class="group rounded-[26px] border border-white/70 bg-white/90 p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="rounded-2xl bg-emerald-100 p-3 text-emerald-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 7h8"></path>
                                    <path d="M8 11h8"></path>
                                    <path d="M8 15h5"></path>
                                    <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-emerald-600">Publik</span>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-gray-900">Absen Anggota</h3>
                        <p class="mt-1 text-sm text-gray-600">Check-in dan check-out kegiatan.</p>
                    </a>

                    <a href="{{ route('asset.public.loan') }}"
                        class="group rounded-[26px] border border-white/70 bg-white/90 p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="rounded-2xl bg-blue-100 p-3 text-blue-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9h18"></path>
                                    <path d="M7 9V6a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v3"></path>
                                    <rect x="3" y="9" width="18" height="11" rx="2"></rect>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-600">Publik</span>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-gray-900">Peminjaman Inventaris</h3>
                        <p class="mt-1 text-sm text-gray-600">Ajukan pinjam aset dan cek ketersediaan.</p>
                    </a>

                    <a href="{{ route('member.self-service') }}"
                        class="group rounded-[26px] border border-white/70 bg-white/90 p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="rounded-2xl bg-amber-100 p-3 text-amber-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4z"></path>
                                    <path d="M4 20a8 8 0 0 1 16 0"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-amber-600">Login</span>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-gray-900">Self Service Anggota</h3>
                        <p class="mt-1 text-sm text-gray-600">Update data pribadi dengan NIA.</p>
                    </a>

                    <a href="{{ route('health.public.form') }}"
                        class="group rounded-[26px] border border-white/70 bg-white/90 p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-purple-200 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="rounded-2xl bg-purple-100 p-3 text-purple-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4z"></path>
                                    <path d="M3 21a7 7 0 0 1 14 0"></path>
                                    <path d="M20 8v6"></path>
                                    <path d="M23 11h-6"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-purple-600">Publik</span>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-gray-900">Permohonan Tim Kesehatan</h3>
                        <p class="mt-1 text-sm text-gray-600">Ajukan permohonan layanan kesehatan.</p>
                    </a>

                    <a href="{{ route('dashboard') }}"
                        class="group rounded-[26px] border border-white/70 bg-white/90 p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-200 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="rounded-2xl bg-slate-100 p-3 text-slate-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7" rx="2"></rect>
                                    <rect x="14" y="3" width="7" height="7" rx="2"></rect>
                                    <rect x="14" y="14" width="7" height="7" rx="2"></rect>
                                    <rect x="3" y="14" width="7" height="7" rx="2"></rect>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-600">Dashboard</span>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-gray-900">Akses Dashboard</h3>
                        <p class="mt-1 text-sm text-gray-600">Lanjutkan ke panel admin setelah login.</p>
                    </a>

                    <a href="https://ksrpmiunp.or.id" target="_blank" rel="noopener noreferrer"
                        class="group rounded-[26px] border border-white/70 bg-white/90 p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-rose-200 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="rounded-2xl bg-rose-100 p-3 text-rose-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 8v8"></path>
                                    <path d="M8 12h8"></path>
                                    <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-rose-600">Info</span>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-gray-900">Pendaftaran Baru</h3>
                        <p class="mt-1 text-sm text-gray-600">Arahkan calon anggota untuk registrasi.</p>
                    </a>
                </div>
            </section>
        </main>
    </div>
@endsection
