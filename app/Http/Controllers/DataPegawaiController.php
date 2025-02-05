<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

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
            ->paginate(5);

        return view('pages.datapegawai', compact('pegawais'));
    }

    public function create()
    {
        $jabatans = Jabatan::all(); // Ambil semua data jabatan
        return view('pages.createpegawai', compact('jabatans'));
    }

    public function store(Request $request)
{
    // Mulai transaksi
    DB::beginTransaction();

    try {
        // Validasi manual menggunakan Validator
        $validator = Validator::make($request->all(), [
            'nik' => 'required|digits:16|unique:pegawais', // Validasi NIK 16 digit
            'nama' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255', // Validasi nama hanya boleh huruf dan spasi
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'jabatans_id' => 'required|exists:jabatans,id',
            'mulai_kerja' => 'required|date',
            'lama_kerja' => 'required|integer|min:1|max:30',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Mengembalikan input dan error message jika validasi gagal
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Menangani file foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('uploads', 'public');
        }

        // Buat data pegawai
        $pegawai = new Pegawai();
        $pegawai->nik = $request->nik;
        $pegawai->nama = $request->nama;
        $pegawai->tanggal_lahir = $request->tanggal_lahir;
        $pegawai->alamat = $request->alamat;
        $pegawai->jabatans_id = $request->jabatans_id;
        $pegawai->mulai_kerja = $request->mulai_kerja;
        $pegawai->lama_kerja = $request->lama_kerja;

        // Hitung selesai kerja
        $mulaiKerja = Carbon::parse($request->mulai_kerja);
        $pegawai->selesai_kerja = $mulaiKerja->addYears((int) $request->lama_kerja)->format('Y-m-d');

        // Simpan foto
        $pegawai->foto = $fotoPath ?? null;

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

        return redirect()->route('pegawai.data.index')->with('success', 'Data berhasil disimpan!');
    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        DB::rollBack();
        Log::error('Error: ' . $e->getMessage());
        return redirect()
            ->back()
            ->withInput()
            ->with('error', "Terjadi kesalahan: form wajib di isi semua ");
    }
}

    

    private function generateUniqueUsername($nama, $nik)
    {
        $baseUsername = strtolower(str_replace(' ', '', $nama)) . $nik;
        $username = $baseUsername;
        
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
        $jabatans = Jabatan::all();
        return view('pages.editpegawai', compact('pegawai', 'jabatans'));
    }

    public function update(Request $request, $id)
    {
        // Mulai transaksi
        DB::beginTransaction();

        try {
            $pegawai = Pegawai::findOrFail($id);

            $request->validate([
                'nik' => 'required|digits:16|unique:pegawais,nik,' . $id, // Validasi NIK 16 digit
                'nama' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255', // Validasi nama hanya boleh huruf dan spasi
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'jabatans_id' => 'required|exists:jabatans,id',
                'mulai_kerja' => 'required|date',
                'lama_kerja' => 'required|integer|min:1|max:30',
                'foto' => $request->hasFile('foto') ? 'image|mimes:jpeg,png,jpg|max:2048' : '',
            ]);
            

            $pegawai->nik = $request->nik;
            $pegawai->nama = $request->nama;
            $pegawai->tanggal_lahir = $request->tanggal_lahir;
            $pegawai->alamat = $request->alamat;
            $pegawai->jabatans_id = $request->jabatans_id;
            $pegawai->mulai_kerja = $request->mulai_kerja;
            $pegawai->lama_kerja = $request->lama_kerja;

            // Hitung selesai kerja
            $mulaiKerja = Carbon::parse($request->mulai_kerja);
            $pegawai->selesai_kerja = $mulaiKerja->addYears((int) $request->lama_kerja)->format('Y-m-d');

            // Simpan foto jika ada perubahan
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('uploads', 'public');
                $pegawai->foto = $fotoPath;
            }

            $pegawai->save();

            // Commit transaksi
            DB::commit();

            return redirect()
                ->route('pegawai.data.index')
                ->with('success', 'Data pegawai berhasil diperbarui.');

        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pages.viewpegawai', compact('pegawai'));
    }

    public function destroy($id)
    {
        // Mulai transaksi
        DB::beginTransaction();

        try {
            $pegawai = Pegawai::findOrFail($id);

            // Delete related user account
            if ($user = User::where('pegawai_id', $pegawai->id)->first()) {
                // Delete related pelatihan data first
                Pelatihan::where('user_id', $user->id)->delete();
                
                // Then delete the user
                $user->delete();
            }

            // Finally delete the pegawai
            $pegawai->delete();

            // Commit transaksi
            DB::commit();

            return redirect()
                ->route('pegawai.data.index')
                ->with('success', 'Pegawai dan data terkait berhasil dihapus.');
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
            return redirect()
                ->route('pegawai.data.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
