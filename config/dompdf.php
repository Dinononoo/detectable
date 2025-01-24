<?php

return [
    'show_warnings' => false,
    'orientation' => 'portrait',
    'default_font' => 'THSarabunNew',
    
    // กำหนด path ของฟอนต์
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),
    
    // เพิ่มการตั้งค่าสำหรับภาษาไทย
    'enable_php' => true,
    'enable_javascript' => true,
    'enable_remote' => true,
    'enable_font_subsetting' => true,
    
    'temp_dir' => storage_path('app/dompdf'),
    'chroot' => realpath(base_path()),
    'allowed_protocols' => [
        'file://' => ['rules' => []],
        'http://' => ['rules' => []],
        'https://' => ['rules' => []]
    ],
];
