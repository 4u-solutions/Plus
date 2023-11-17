<?php

namespace App\Http\Controllers\exports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Helper\Helper;

class reportePorPax implements FromView,WithDrawings
{
    protected $data, $fecha;
    public function __construct($data, $fecha){
        $this->data  = $data;
        $this->fecha = $fecha;
    }

    public function drawings()
    {
        $public_path = (strpos(getcwd(), 'themanorgt') ? getcwd() :  (substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/public')) . '/';

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo - test'.Helper::get_platform());
        $drawing->setPath($public_path . 'img_admin/logo-plus-negro.png');
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function view(): View
    {
        return view('admin.reportes.pax_por_reservacion',["data"=>$this->data, 'fecha' => $this->fecha]);
    }

    public function preViewView()
    {
      return view('admin.reportes.pax_por_reservacion',["data"=>$this->data, 'fecha' => $this->fecha]);
    }
}
