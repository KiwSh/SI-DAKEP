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

        // Perbaikan: Ubah 'file' menjadi 'excel_file'
        if (!$request->hasFile('excel_file')) {
            Log::error('No file uploaded.');
            return response()->json(['error' => 'No file uploaded.'], 422);
        }
        
        $file = $request->file('excel_file');

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
            return back()->with('success', 'Data berhasil diimpor.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error importing data: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error importing data: ' . $e->getMessage()], 500);
        }
    }
}
