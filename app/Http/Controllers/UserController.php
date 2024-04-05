<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data yang dikirimkan oleh formulir
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:3|max:255',
            ]);

            // dd($request->all());

            // Simpan data pengguna baru ke dalam database
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password), // Enkripsi password sebelum disimpan
            ]);

            // Redirect ke halaman index dengan pesan sukses
            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (QueryException $e) {
            // Tangkap kesalahan SQL dan tampilkan pesan kesalahan
            return back()->withInput()->withErrors(['error' => 'Gagal menambahkan user. Terjadi kesalahan: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            // Tangkap kesalahan umum dan tampilkan pesan kesalahan
            return back()->withInput()->withErrors(['error' => 'Gagal menambahkan user. Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            // Cari pengguna berdasarkan ID
            $user = User::findOrFail($id);

            // Tampilkan view edit dengan data pengguna yang akan diedit
            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            // Tangkap kesalahan dan tampilkan pesan kesalahan
            return back()->withErrors(['error' => 'Gagal mengedit user. Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi data yang dikirimkan oleh formulir
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'nullable|string|min:3|max:255', // password dapat kosong
            ]);

            // Cari pengguna berdasarkan ID
            $user = User::findOrFail($id);

            // Perbarui data pengguna berdasarkan data yang dikirimkan melalui formulir
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password) {
                $user->password = bcrypt($request->password); // Enkripsi password jika ada
            }
            $user->save();

            // Redirect ke halaman index dengan pesan sukses
            return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangkap kesalahan dan tampilkan pesan kesalahan
            return back()->withErrors(['error' => 'Gagal memperbarui user. Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Cari pengguna berdasarkan ID
            $user = User::findOrFail($id);

            // Hapus pengguna dari database
            $user->delete();

            // Redirect ke halaman index dengan pesan sukses
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangkap kesalahan dan tampilkan pesan kesalahan
            return back()->withErrors(['error' => 'Gagal menghapus user. Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
