<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
Use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MachineExport implements FromView, ShouldAutoSize, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View {
        return view('exports.machine', ['data' => $this->data]);
	}

    public function title(): string
    {
        return 'Machine';
    }

    //for designs
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // $event->sheet->getDelegate()->getStyle('C1')->getFont()->setSize(30);
                // $event->sheet->getDelegate()->getStyle('A2:X2')->getFont()->setSize(13);
            },
        ];
    }
}
