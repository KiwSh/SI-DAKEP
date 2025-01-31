<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;



class DataPegawaiController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $pegawais = Pegawai::with('jabatan')
        ->when($search, function ($query, $search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%")
                ->orWhere('mulai_kerja', 'like', "%{$search}%")
                ->orWhereHas('jabatan', function ($q) use ($search) {
                    $q->where('nama_jabatan', 'like', "%{$search}%");
                });
        })
        ->get();

    return view('pages.datapegawai', compact('pegawais'));
}




    public function create()
    {
        $jabatans = Jabatan::all(); // Ambil semua data jabatan
        return view('pages.createpegawai', compact('jabatans'));
    }

        public function store(Request $request)
    {
        try {
            $request->validate([
                'nik' => 'required|string|unique:pegawais',
                'nama' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'jabatans_id' => 'required|exists:jabatans,id',
                'mulai_kerja' => 'required|date',
                'lama_kerja' => 'required|integer|min:1|max:30',
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'required' => ':attribute wajib diisi.',
                'unique' => ':attribute sudah terdaftar.',
                'exists' => ':attribute tidak valid.',
                'date' => ':attribute harus berupa tanggal yang valid.',
                'integer' => ':attribute harus berupa angka.',
                'min' => ':attribute minimal :min.',
                'max' => ':attribute maksimal :max.',
                'image' => ':attribute harus berupa gambar.',
                'mimes' => ':attribute harus berformat jpeg, png, atau jpg.',
                'foto.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
            ]);

            // Mulai database transaction
        DB::beginTransaction();

        // Buat data pegawai
        $pegawai = new Pegawai();
        $pegawai->nik = $request->nik;
        $pegawai->nama = $request->nama;
        $pegawai->tanggal_lahir = $request->tanggal_lahir;
        $pegawai->alamat = $request->alamat;
        $pegawai->jabatans_id = $request->jabatans_id;
        $pegawai->mulai_kerja = $request->mulai_kerja;
        $pegawai->lama_kerja = $request->lama_kerja;

        // Hitung selesai kerja menggunakan Carbon
        $mulaiKerja = \Carbon\Carbon::parse($request->mulai_kerja);
        $pegawai->selesai_kerja = $mulaiKerja->addYears((int) $request->lama_kerja)->format('Y-m-d');

        // Simpan foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('uploads', 'public');
            $pegawai->foto = $fotoPath;
        }

        $pegawai->save();

        // Buat akun user otomatis
        $user = User::create([
            'nama_user' => $pegawai->nama,
            'username' => $this->generateUniqueUsername($pegawai->nama, $pegawai->nik),
            'password' => Hash::make($pegawai->nik), // Gunakan NIK sebagai password awal
            'role' => 'user', // Sesuaikan dengan kebutuhan
            'pegawai_id' => $pegawai->id
        ]);

        // Commit transaksi
        DB::commit();

        return redirect()
            ->route('pegawai.data.index')
            ->with('success', 'Data pegawai berhasil disimpan. Akun user telah dibuat.');

    } catch (ValidationException $e) {
        // Rollback transaksi jika validasi gagal
        DB::rollBack();

        return redirect()
            ->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'Form Data Harus Terisi Semua. Silakan periksa kembali data yang diinput.');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollBack();

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    }
}

// Method helper untuk generate username unik
private function generateUniqueUsername($nama, $nik)
{
    // Buat base username dari nama dan NIK
    $baseUsername = strtolower(str_replace(' ', '', $nama)) . $nik;
    $username = $baseUsername;
    
    // Cek apakah username sudah ada
    $count = 1;
    while (User::where('username', $username)->exists()) {
        $username = $baseUsername . $count;
        $count++;
    }
    
    return $username;
}


    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $jabatans = Jabatan::all(); // Ambil semua data jabatan untuk dropdown
        return view('pages.editpegawai', compact('pegawai', 'jabatans'));
    }

    public function update(Request $request, $id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);

            $request->validate([
                'nik' => 'required|string|unique:pegawais,nik,' . $id,
                'nama' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'jabatans_id' => 'required|exists:jabatans,id',
                'mulai_kerja' => 'required|date',
                'lama_kerja' => 'required|integer|min:1|max:30',
                'foto' => $request->hasFile('foto') ? 'image|mimes:jpeg,png,jpg|max:2048' : '', // Validasi foto jika ada
            ], [
                'required' => ':attribute wajib diisi.',
                'unique' => ':attribute sudah terdaftar.',
                'exists' => ':attribute tidak valid.',
                'date' => ':attribute harus berupa tanggal yang valid.',
                'integer' => ':attribute harus berupa angka.',
                'min' => ':attribute minimal :min.',
                'max' => ':attribute maksimal :max.',
                'image' => ':attribute harus berupa gambar.',
                'mimes' => ':attribute harus berformat jpeg, png, atau jpg.',
                'foto.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
            ]);

            $pegawai->nik = $request->nik;
            $pegawai->nama = $request->nama;
            $pegawai->tanggal_lahir = $request->tanggal_lahir;
            $pegawai->alamat = $request->alamat;
            $pegawai->jabatans_id = $request->jabatans_id;
            $pegawai->mulai_kerja = $request->mulai_kerja;
            $pegawai->lama_kerja = $request->lama_kerja;

            // Hitung selesai kerja
            $mulaiKerja = \Carbon\Carbon::parse($request->mulai_kerja);
            $pegawai->selesai_kerja = $mulaiKerja->addYears((int) $request->lama_kerja)->format('Y-m-d');

            // Simpan foto jika ada perubahan
                if ($request->hasFile('foto')) {
                    $fotoPath = $request->file('foto')->store('uploads', 'public');
                    $pegawai->foto = $fotoPath;
                }

            $pegawai->save();

            return redirect()
                ->route('pegawai.data.index')
                ->with('success', 'Data pegawai berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Form Data Update Harus Terisi Semua. Silakan periksa kembali data yang diupdate.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id); // Ambil data pegawai berdasarkan ID
        return view('pages.viewpegawai', compact('pegawai')); // Return ke view detail pegawai
    }


    public function destroy($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
            $pegawai->delete();

            return redirect()->route('pegawai.data.index')->with('success', 'Pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('pegawai.data.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
