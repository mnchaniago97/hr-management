@php
    $labels = $chartData['labels'] ?? [];
    $attendanceSeries = $chartData['attendance'] ?? [];
    $loanSeries = $chartData['loans'] ?? [];
    $healthSeries = $chartData['health'] ?? [];
@endphp

<div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Statistik 30 Hari Terakhir
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Absensi, peminjaman inventaris, dan permintaan layanan kesehatan.
            </p>
        </div>
        <div class="inline-flex flex-wrap gap-2 rounded-full bg-gray-100 p-1 text-sm dark:bg-gray-900">
            <button data-chart-tab="attendance" class="chart-tab-btn rounded-full px-4 py-2 font-medium text-gray-700 shadow-theme-xs bg-white dark:bg-gray-800 dark:text-white">
                Absensi
            </button>
            <button data-chart-tab="loans" class="chart-tab-btn rounded-full px-4 py-2 font-medium text-gray-500 dark:text-gray-400">
                Peminjaman
            </button>
            <button data-chart-tab="health" class="chart-tab-btn rounded-full px-4 py-2 font-medium text-gray-500 dark:text-gray-400">
                Tim Kesehatan
            </button>
        </div>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <div id="chartThree"
            data-chart-labels='@json($labels)'
            data-chart-attendance='@json($attendanceSeries)'
            data-chart-loans='@json($loanSeries)'
            data-chart-health='@json($healthSeries)'
            class="-ml-4 min-w-[700px] pl-2 xl:min-w-full"></div>
    </div>
</div>
