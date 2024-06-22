<?php

namespace App\Exports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class DocumentsExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithTitle
{
    private $documentsIDs = [];

    public function __construct($documentsIDs = False){

        $this->documentsIDs = $documentsIDs;
    }

    public function styles(Worksheet $sheet)
    {
        return [
           // Style the first row as bold text.
           1    => ['font' => ['bold' => true, 'alignment' => 'center']],
        ];
        // $sheet->getStyle('1')->getFont()->setBold(true);
    }

    public function headings(): array
    {
        return [
            __('Title'),
            __('File EMB'),
            __('Comment'),
            __('Updated at'),
        ];
    }

    /**
    * @var Invoice $document
    */
    public function map($document): array
    {
        return [
            $document->title,
            $document->file_emb,
            $document->comment,
            $document->updated_at,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Documents_').' '.now()->format('g:i a l jS F Y');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Document::all()->sortByDesc('created_at');
    }
}
