<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function dashboard() {
        return response()->json([
            "messgae" => "Halaman Mahasiswa"
        ]);
    }


    //Inventory Routes Mahasiswa
    public function GetMyInventory() {
        $inventory = auth()->user()
        ->inventories()
        ->latest()
        ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $inventory
        ]);
    }
}
