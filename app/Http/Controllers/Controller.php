<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Filter data berdasarkan ponpes_id user login.
     */
    protected function filterByPonpes($table)
    {
        $userPonpesId = Auth::user()->ponpes_id;

        return DB::table($table)->where('ponpes_id', $userPonpesId);
    }

    /**
     * Proteksi detail/update/delete.
     */
    protected function checkOwnership($table, $id)
    {
        $userPonpesId = Auth::user()->ponpes_id;

        return DB::table($table)
            ->where('id', $id)
            ->where('ponpes_id', $userPonpesId)
            ->first();
    }
}
