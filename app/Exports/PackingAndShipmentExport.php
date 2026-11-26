<?php

namespace App\Exports;

use App\Model\ShippingInspector;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PackingAndShipmentExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return ShippingInspector::all();
    // }

    protected $records;
    protected $device_name;

    function __construct($records, $device_name)
    {
        $this->records = $records;
        $this->device_name = $device_name;
    }

    public function view(): View
    {
    	$shipping_records = $this->records;
    	$device_name = $this->device_name;

        return view('exports.packingandshipment', compact('shipping_records', 'device_name'));
    }
}
