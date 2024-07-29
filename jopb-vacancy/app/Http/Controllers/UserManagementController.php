<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.user-management', compact('users'));
    }
    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        // Validasi input
        // Simpan data pengguna
        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->name = 'Administrator';

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'User added successfully.');
    }

    // Menampilkan form edit pengguna
    public function edit($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        return response()->json($user);
    }

    // Mengupdate data pengguna
    public function update(Request $request, $id)
    {
        // Validasi input

        // Update data pengguna
        $user = User::findOrFail($id);
        $user->username = $request->input('username');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }

            // Simpan foto baru
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'User updated successfully.');
    }

    // Menghapus data pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus foto jika ada
        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
        }

        $user->delete();

        return redirect()->route('profile')->with('success', 'User deleted successfully.');
    }
}
