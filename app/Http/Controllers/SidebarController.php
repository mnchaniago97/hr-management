<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hr\Member;
use App\Models\Asset\AssetItem;
use App\Models\Program\Program;
use App\Models\Program\Activity;
use App\Models\Program\Period;

class SidebarController extends Controller
{
    public function getMenuData()
    {
        $menuGroups = [
            [
                'title' => 'Dashboard',
                'items' => [
                    [
                        'icon' => 'home-icon',
                        'name' => 'Dashboard',
                        'path' => '/dashboard',
                    ],
                ],
            ],
            [
                'title' => 'Program Kerja',
                'items' => [
                    [
                        'icon' => 'calendar-icon',
                        'name' => 'Master Data',
                        'subItems' => [
                            ['name' => 'Periode', 'path' => '/program/periods'],
                            ['name' => 'Bidang', 'path' => '/program/fields'],
                            ['name' => 'Divisi', 'path' => '/program/divisions'],
                        ],
                    ],
                    [
                        'icon' => 'folder-icon',
                        'name' => 'Program',
                        'subItems' => [
                            ['name' => 'Semua Program', 'path' => '/program/programs'],
                        ],
                    ],
                    [
                        'icon' => 'activity-icon',
                        'name' => 'Kalender Kegiatan',
                        'path' => '/program/activities/calendar',
                    ],
                    [
                        'icon' => 'list-icon',
                        'name' => 'Daftar Kegiatan',
                        'path' => '/program/activities',
                    ],
                    [
                        'icon' => 'document-icon',
                        'name' => 'Dokumen',
                        'subItems' => [
                            ['name' => 'Semua Dokumen', 'path' => '/program/documents'],
                            ['name' => 'Upload Dokumen', 'path' => '/program/documents/create'],
                        ],
                    ],
                    [
                        'icon' => 'chart-icon',
                        'name' => 'Evaluasi & Laporan',
                        'subItems' => [
                            ['name' => 'Ringkasan Laporan', 'path' => '/program/reports'],
                            ['name' => 'Rekap per Periode', 'path' => '/program/reports/by-period'],
                            ['name' => 'Rekap per Bidang', 'path' => '/program/reports/by-field'],
                            ['name' => 'Rekap per Divisi', 'path' => '/program/reports/by-division'],
                            ['name' => 'Rekap per Bulan', 'path' => '/program/reports/monthly'],
                            ['name' => 'Export Laporan', 'path' => '/program/reports/export'],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'HR / Keanggotaan',
                'items' => [
                    [
                        'icon' => 'users-icon',
                        'name' => 'Keanggotaan',
                        'subItems' => [
                            ['name' => 'Semua Anggota', 'path' => '/hr/members'],
                            ['name' => 'Import Anggota', 'path' => '/hr/members/import'],
                            ['name' => 'Kartu Anggota', 'path' => '/hr/members/cards'],
                        ],
                    ],
                    [
                        'icon' => 'briefcase-icon',
                        'name' => 'Jabatan & Penempatan',
                        'subItems' => [
                            ['name' => 'Jabatan', 'path' => '/hr/positions'],
                            ['name' => 'Penempatan Anggota', 'path' => '/hr/assignments'],
                        ],
                    ],
                    [
                        'icon' => 'clipboard-icon',
                        'name' => 'Absensi',
                        'path' => '/hr/attendance',
                    ],
                    [
                        'icon' => 'calendar-event-icon',
                        'name' => 'Cuti',
                        'path' => '/hr/leave-requests',
                    ],
                    [
                        'icon' => 'user-plus-icon',
                        'name' => 'Rekrutmen',
                        'subItems' => [
                            ['name' => 'Daftar Calon', 'path' => '/hr/recruitment'],
                            ['name' => 'Seleksi', 'path' => '/hr/recruitment/selection'],
                        ],
                    ],
                    [
                        'icon' => 'book-icon',
                        'name' => 'Pelatihan',
                        'subItems' => [
                            ['name' => 'Daftar Pelatihan', 'path' => '/hr/trainings'],
                            ['name' => 'Peserta Pelatihan', 'path' => '/hr/training-participants'],
                        ],
                    ],
                    [
                        'icon' => 'folder-user-icon',
                        'name' => 'Sertifikasi',
                        'path' => '/hr/certifications',
                    ],
                ],
            ],
            [
                'title' => 'Asset / Inventaris',
                'items' => [
                    [
                        'icon' => 'database-icon',
                        'name' => 'Master Data',
                        'subItems' => [
                            ['name' => 'Kategori Aset', 'path' => '/asset/categories'],
                        ],
                    ],
                    [
                        'icon' => 'box-icon',
                        'name' => 'Data Aset',
                        'subItems' => [
                            ['name' => 'Semua Aset', 'path' => '/asset/items'],
                            ['name' => 'Tambah Aset', 'path' => '/asset/items/create'],
                            ['name' => 'QR Code', 'path' => '/asset/items/qrcode'],
                        ],
                    ],
                    [
                        'icon' => 'exchange-icon',
                        'name' => 'Peminjaman',
                        'subItems' => [
                            ['name' => 'Peminjaman Aset', 'path' => '/asset/loans'],
                            ['name' => 'Pengembalian', 'path' => '/asset/loans/return'],
                            ['name' => 'Terlambat', 'path' => '/asset/loans/overdue'],
                            ['name' => 'Riwayat Penggunaan', 'path' => '/asset/history'],
                        ],
                    ],
                    [
                        'icon' => 'tool-icon',
                        'name' => 'Maintenance',
                        'subItems' => [
                            ['name' => 'Perbaikan / Service', 'path' => '/asset/maintenance'],
                            ['name' => 'Jadwal Perawatan', 'path' => '/asset/maintenance/upcoming'],
                        ],
                    ],
                ],
            ],
        ];

        return view('layouts.sidebar', compact('menuGroups'));
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $currentPeriod = Period::where('is_active', true)->first();
        
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::where('status', 'active')->count(),
            'am_count' => Member::where('member_type', 'AM')->count(),
            'at_count' => Member::where('member_type', 'AT')->count(),
            
            'total_assets' => AssetItem::count(),
            'available_assets' => AssetItem::where('status', 'available')->count(),
            'borrowed_assets' => AssetItem::where('status', 'borrowed')->count(),
            'maintenance_assets' => AssetItem::where('status', 'maintenance')->count(),
            
            'total_programs' => Program::count(),
            'active_programs' => Program::where('status', 'active')->count(),
            'terprogram_count' => Program::where('type', 'terprogram')->count(),
            'insidentil_count' => Program::where('type', 'insidentil')->count(),
            
            'total_activities' => Activity::count(),
            'upcoming_activities' => Activity::where('status', 'publish
ed')->where('start_date', '>=', now())->count(),
            'ongoing_activities' => Activity::where('status', 'ongoing')->count(),
        ];

        return $stats;
    }
}
