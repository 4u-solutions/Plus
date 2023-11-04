<?php

namespace App\Imports;

use App\Models\userTicketsModel;
use App\Models\userModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\Controller;
use QrCode;
use PDF;
use Mail;
use Storage;
use App\Mail\sendMail;

class studentsImport implements ToCollection, WithHeadingRow
{
    protected $id;
    protected $yid;
    private $badRows;
    public function __construct(){
      $this->controller=new Controller;
    }
    public function collection(Collection $rows)
    {
        $data=$bads=$toGrade=[];
        foreach ($rows as $ields)
        {
          $arrBad=0;
          $fields=$ields->all();
          $newes = userModel::where("email",$fields["e_mail_del_cliente"])->first();
          // dd($fields);
          if(!empty($newes)){
            // dd($newes);
            $alreadyexists = userTicketsModel::where("authorization",$fields["no_autorizacion"])->first();
            if(empty($alreadyexists)){
              $getNum = (int) filter_var($fields["descripcion"], FILTER_SANITIZE_NUMBER_INT);
              $cantidad = $fields["importe"] / $getNum;
              $codes = [];
              for($i=0;$i<$cantidad;$i++){
                $codes[] = $this->controller->randem(4).'-'.$this->controller->randem(4).'-'.$this->controller->randem(4);
              }
              $modelStu=new userTicketsModel;
              $modelStu->concert='canibal corpse 24 mayo 2022';
              $modelStu->authorization=$fields["no_autorizacion"];
              $modelStu->userN=$fields["nombre_del_cliente"];
              $modelStu->typePayment=$fields["origen"];
              $modelStu->tj=$fields["numero_tarjeta"];
              $modelStu->userN=$fields["nombre_del_cliente"];
              $modelStu->store=$fields["nombre_de_comercio"];
              $modelStu->areas=$fields["nombre_de_sucursal"];
              $modelStu->descrip=$fields["descripcion"];
              $modelStu->idUser=$newes->id;
              $modelStu->total=$fields["importe"];
              $modelStu->cost=$getNum;
              $modelStu->quantity=$cantidad;
              $modelStu->bouthDate=$fields[""];
              $modelStu->codesR=json_encode($codes);
              $modelStu->statusMail = 'Enviado en carga';
              $modelStu->save();

              $fileQr = [];
              $exCode = $codes;
              for($i=0;$i<$cantidad;$i++){
                $png = QrCode::format('png')->size(350)->generate($exCode[$i]);
                $png = base64_encode($png);
                $data = ['qrimage'=>$png,
                         'cod'=>$exCode[$i],
                         'name'=>$newes->name." ".$newes->lastname,
                         'phone'=>$newes->phone,
                         'mail'=>$newes->email,
                         'date'=>date("d/m/Y",strtotime($fields[""])),
                         'soldin'=>'BAC',
                         'cost'=>$getNum,];
                $customPaper = [0,0,335,750];
                $pdf = PDF::loadView('admin.pdf.show', $data)
                          ->setPaper($customPaper, 'portrait');
                $test = Storage::put('public/pdf/'.$exCode[$i].'.pdf',$pdf->output()) ;
                // dd($test);
                if (Storage::exists('public/pdf/'.$exCode[$i].'.pdf')) {
                  $fileQr[]= public_path('storage/pdf/'.$exCode[$i].'.pdf');
                }
                // dd($test);
              }
              // dd($fileQr);
              $data = ["mail"=>$newes->email,
                       "name"=>$newes->name." ".$newes->lastname,
                       "subject"=>'Boleto(s) Canibal Corpse 2022',
                       "attachemnts"=>$fileQr];
                       // ->to($this->data["mail"], $this->data["name"])
             Mail::to($newes->email)->send(new sendMail($data));

            }
          }
        }
    }
}
