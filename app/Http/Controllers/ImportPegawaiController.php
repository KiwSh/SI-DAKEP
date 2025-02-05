<?php

namespace App\Http\Controllers;

use App\Imports\PegawaiImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImportPegawaiController extends Controller
{
    public function importExcel(Request $request)
    {
        Log::info('Starting file upload process...');
        Log::info('Request details:', $request->all());

        // Ubah pengecekan ke 'file' sesuai dengan name di form
        if (!$request->hasFile('file')) {
            Log::error('No file uploaded.');
            return response()->json(['error' => 'No file uploaded.'], 422);
        }
        
        // Ubah variabel ke 'file'
        $file = $request->file('file');

        Log::info('File details:', [
            'name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $file->getRealPath(),
        ]);

        // Validasi format file
        if (!$file->isValid() || !in_array($file->getClientOriginalExtension(), ['xlsx', 'xls', 'csv'])) {
            Log::error('Invalid file format.');
            return response()->json(['error' => 'File harus dalam format xlsx, xls, atau csv.'], 422);
        }

        try {
            DB::beginTransaction();

            $import = new PegawaiImport;
            Excel::import($import, $file);

            $rowsImported = $import->getRowCount();

            DB::commit();
            Log::info("Data import successful! Rows imported: $rowsImported");
            
            // Ubah return untuk menangani both ajax dan normal submit
            if ($request->ajax()) {
                return response()->json(['success' => 'Data berhasil diimpor.']);
            }
            return back()->with('success', 'Data berhasil diimpor.');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error importing data: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Error importing data: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
}