<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard() {
        return response()->json([
            "messgae" => "Halaman Admin"
        ]);
    }

    // start mahasiswa controller

    public function GetAllDataMahasiswa(Request $request) {

        $request->validate([
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255'
        ]);

        $perPage = $request->per_page ?? 10;
        $page = $request->page ?? 1;
        $search = $request->search;



        $query = User::mahasiswa()
        ->select('id','name','email','role','created_at','updated_at');


        if ($search) {
            $query->where(function($q) use ($search){
                $q->where('name','like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $mahasiswa = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            "success" => true,
            "message" => "Data mahasiswa berhasil diambil",
            "data" => $mahasiswa,
            "total" => $mahasiswa->items(),
            "pagination" => [
                "current_page" => $mahasiswa->currentPage(),
                "per_page" => $mahasiswa->perPage(),
                "total" => $mahasiswa->total(),
                "last_page" => $mahasiswa->lastPage(),
                "from" => $mahasiswa->firstItem(),
                "to" => $mahasiswa->lastItem(),
            ],
            "total" => $mahasiswa->total()
        ]);
    }

    public function GetAllDataMahasiswaPaginated(Request $request) {
        $perPage = $request->get('per_page', 10);
        $mahasiswa = User::mahasiswa()
        ->select('id', 'name', 'email', 'role', 'created_at', 'updated_at')
        ->paginate($perPage);

        return response()->json([
            "success" => true,
            "message" => "Data mahasiswa berhasil diambil",
            "data" => $mahasiswa->items(),
            "pagination" => [
                "current_page" => $mahasiswa->currentPage(),
                "per_page" => $mahasiswa->perPage(),
                "total" => $mahasiswa->total(),
                "last_page" => $mahasiswa->lastPage()
            ]
        ]);
    }


    public function GetMahasiswaById($id) {
        $mahasiswa = User::mahasiswa()
        ->where('id',$id)
        ->select("id","name","email","role","created_at","updated_at")
        ->first();

        if (!$mahasiswa) {
            return response()->json([
                "success" => false,
                "message" => "Data mahasiswa tidak ditemukan",
            ],404); 
        }

        return response()->json([
            "success" => true,
            "message" => "Data Mahasiswa berhasil diambil",
            "Data" => $mahasiswa
        ]);
    }

    public function CreateDataMahasiswa(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ],422);
        }

        $mahasiswa = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        return response()->json([
            "success" => true,
            "message" => "Data Mahasiswa berhasil dibuat",
            "data" => [
                'id' => $mahasiswa->id,
                'name' => $mahasiswa->name,
                'email' => $mahasiswa->email,
                'role' => $mahasiswa->role
            ]
        ],201);
    } 

    public function UpdateDataMahasiswa(Request $request, $id) {
        $mahasiswa = User::mahasiswa()->where('id',$id)->first();

        if (!$mahasiswa) {
            return response()->json([
                "success" => false,
                "message" => "Data mahasiswa tidak ada"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:5'
        ]);

        if (!$validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Data Mahasiswa gagal terupdate"
            ],422);
        }

        $updatedData = [];
        if ($request->has('name')) {
            $updatedData['name'] = $request->name;
        }
        if ($request->has('email')) {
            $updatedData['email'] = $request->email;
        }
        if ($request->has('password')) {
            $updatedData['password'] = $request->password;
        }

        $mahasiswa->update($updatedData);
    }

    public function DeleteDataMahasiswa($id) {
        $mahasiswa = User::mahasiswa()->where('id',$id)->first();

        if (!$mahasiswa) {
            return response()->json([
                "success" => false,
                "message" => "Data Mahasiswa tidak ditemukan"
            ],404);
        }
        $mahasiswa->delete();

        return response()->json([
            "success" => true,
            "message" => "Data Mahasiswa berhasil dihapus"
        ]);
    }

    // end mahasiswa controller

    // Inventory Controller Start

    public function GetAllDataInventory() {
        $inventory = Inventory::with('user:id,name,email')
        ->latest()
        ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $inventory
        ]);
    }




    public function AddDataInventory(Request $request) {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'quantity' => 'required|integer|min:8',
            'image_barang' => 'nullable|image|max:2048',
            'tanggal_pinjam' => 'required',
            'tanggal_kembali' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('image_barang')) {
            $validated['image_barang'] = $request->file('image_barang')->store('inventory','public');
        }
        
        Inventory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data inventory berhasil di tambahkan'
        ]);
    }
} 
