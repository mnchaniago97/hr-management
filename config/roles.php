<?php

return [
    'enforce' => false,
    'roles' => [
        'Super Admin' => [
            'hr.access',
            'asset.access',
            'program.access',
        ],
        'DPH' => [
            'hr.access',
            'asset.access',
            'program.access',
        ],
        'Koordinator Bidang' => [
            'program.access',
            'hr.access',
            'asset.access',
        ],
        'Koordinator Divisi' => [
            'program.access',
            'hr.access',
            'asset.access',
        ],
        'Anggota' => [
            'program.access',
            'hr.access',
            'asset.access',
        ],
    ],
];
