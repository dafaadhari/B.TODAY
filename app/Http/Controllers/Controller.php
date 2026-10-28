<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Pilihan jumlah baris per halaman di tabel admin.
     *
     * @var list<int>
     */
    public const ADMIN_PER_PAGE_OPTIONS = [5, 10, 25];

    /**
     * Jumlah baris per halaman tabel admin: pilihan admin (`?per_halaman=`) bila valid,
     * selain itu 5 di HP dan 10 di desktop. HP dikenali dari "Mobi" pada user-agent.
     */
    protected function adminPerPage(Request $request): int
    {
        $requested = (int) $request->query('per_halaman');

        if (in_array($requested, self::ADMIN_PER_PAGE_OPTIONS, true)) {
            return $requested;
        }

        return str_contains((string) $request->userAgent(), 'Mobi') ? 5 : 10;
    }
}
