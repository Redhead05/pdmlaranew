<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mapping menu sidebar -> permission
    |--------------------------------------------------------------------------
    | Key   : identifier menu
    | value : permission name + label tampilan
    */
    'menus' => [
        'dashboard'       => ['permission' => 'view dashboard',       'label' => 'Dashboard'],
        'user'            => ['permission' => 'manage users',         'label' => 'User'],
        'user-management' => ['permission' => 'akses user management','label' => 'User Management'],
        'attendance'      => ['permission' => 'akses attendance',     'label' => 'Attendance'],
        'master-lembaga'  => ['permission' => 'akses master lembaga', 'label' => 'Master Lembaga'],
        'certifications'  => ['permission' => 'akses certifications', 'label' => 'Certifications'],
        'visitasi'        => ['permission' => 'akses visitasi',       'label' => 'Visitasi'],
        'validasi'        => ['permission' => 'akses validasi',       'label' => 'Validasi'],
        'berkas-visitasi' => ['permission' => 'akses berkas visitasi','label' => 'Berkas Visitasi'],
        'ticket-support'  => ['permission' => 'akses ticket support', 'label' => 'Ticket Support'],
    ],
];
