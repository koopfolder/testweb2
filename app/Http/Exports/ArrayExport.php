<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithChunkReading;
class ArrayExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithColumnFormatting,WithChunkReading
{


    protected $data;
    protected $header;
    protected $format;

    public function __construct($data = [],$header = [],$format = [])
    {
        $this->data = $data;
        $this->header = $header;
        $this->format = $format;
    }
    public function collection()
    {   
        $data =  $this->data;
        return collect($data);
    }
    public function headings(): array
    {
        return $this->header;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
    // public function columnWidths(): array
    // {
    //     return [
    //         'A' => 20,
    //         'B' => 20,
    //         'C' => 20,
    //         'D' => 20,
    //         'E' => 20,
    //         'F' => 30,
    //         'G' => 20,
    //         'H' => 20,            
    //     ];
    // }
    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle('B2')->getFont()->setBold(true);
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true,'size'=>16]],
        ];
    }

    public function columnFormats(): array
    {
        return $this->format;
    }

}