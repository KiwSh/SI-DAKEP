<?php

namespace App\Exports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PegawaiExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $search;
    protected $jabatanId;
    protected $startDate;
    protected $endDate;
    protected $pegawaiData;

    public function __construct($search = null, $jabatanId = null, $startDate = null, $endDate = null)
    {
        $this->search = $search;
        $this->jabatanId = $jabatanId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Pegawai::query()->with('jabatan');

        // Filter berdasarkan Nama / NIK
        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('nik', 'like', '%' . $this->search . '%');
        }

        // Filter berdasarkan Jabatan
        if ($this->jabatanId) {
            $query->where('tb_jabatan_id', $this->jabatanId);
        }

        // Filter berdasarkan Tanggal Mulai Kerja
        if ($this->startDate) {
            $query->where('mulai_kerja', '>=', $this->startDate);
        }

        // Filter berdasarkan Tanggal Selesai Kerja
        if ($this->endDate) {
            $query->where('mulai_kerja', '<=', $this->endDate);
        }

        $this->pegawaiData = $query->get();
        return $this->pegawaiData;
    }

    public function headings(): array
    {
        return [
            'NIK', 'Nama', 'Jabatan', 'Mulai Kerja', 'Foto'
        ];
    }

    public function map($pegawai): array
    {
        return [
            $pegawai->nik,
            $pegawai->nama,
            $pegawai->jabatan->nama_jabatan ?? '',
            $pegawai->mulai_kerja,
            $pegawai->foto
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $pegawais = $this->collection();
                $row = 2;

                // Style untuk header
                $event->sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Set tinggi baris dan alignment untuk semua baris
                foreach ($pegawais as $index => $pegawai) {
                    $event->sheet->getRowDimension($row)->setRowHeight(100);

                    // Set alignment untuk semua sel di baris ini
                    $event->sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER
                        ]
                    ]);

                    $row++;
                }

                // Set lebar kolom foto
                $event->sheet->getColumnDimension('E')->setWidth(30);

                $row = 2; // Reset row counter untuk gambar
                foreach ($pegawais as $pegawai) {
                    if (!empty($pegawai->foto)) {
                        $fotoPath = str_replace('/storage/', '', $pegawai->foto);
                        $fotoPath = str_replace('http://127.0.0.1:8000/storage/', '', $fotoPath);

                        $fullPath = storage_path('app/public/' . $fotoPath);

                        if (file_exists($fullPath)) {
                            try {
                                $drawing = new Drawing();
                                $drawing->setName('Foto');
                                $drawing->setDescription('Foto');
                                $drawing->setPath($fullPath);
                                $drawing->setHeight(90);
                                $drawing->setWidth(90);
                                $drawing->setCoordinates('E' . $row);

                                // Hitung offset untuk centering
                                $cellWidth = $event->sheet->getColumnDimension('E')->getWidth() * 7; // Approx pixel conversion
                                $cellHeight = $event->sheet->getRowDimension($row)->getRowHeight();
                                $offsetX = ($cellWidth - 90) / 2; // 90 adalah width gambar
                                $offsetY = ($cellHeight - 90) / 2; // 90 adalah height gambar

                                $drawing->setOffsetX($offsetX);
                                $drawing->setOffsetY($offsetY);

                                $drawing->setWorksheet($event->sheet->getDelegate());

                                // Kosongkan nilai sel
                                $event->sheet->setCellValue('E' . $row, '');
                            } catch (\Exception $e) {
                                \Log::error('Error adding image: ' . $e->getMessage());
                            }
                        }
                    }
                    $row++;
                }

                // Auto-size untuk kolom lain
                foreach (range('A', 'D') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Border untuk semua sel
                $lastRow = $row - 1;
                $event->sheet->getStyle('A1:E' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            }
        ];
    }
}
