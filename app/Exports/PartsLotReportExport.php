<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PartsLotReportExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $records;
    protected $device_name;

    function __construct($records, $device_name)
    {
        $this->records = $records;
        $this->device_name = $device_name;
    }

    public function view(): View
    {
        $partsLot_records = $this->records;
        $device_name = $this->device_name;

        return view('exports.partslot', compact('partsLot_records', 'device_name'));
    }
}
