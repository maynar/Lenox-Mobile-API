<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Alerts;
use App\Remitos;
use App\RemitosBody;
use App\Company;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\HistorialJobs;
 
use Illuminate\Support\Facades\Mail;

class Alert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remito:SendMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar correo dependiendo de la accion programada';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date=Carbon::now();
        $week=$date->week();
        $Alerts = Alerts::join('tb_status','tb_status.idStatus', '=', 'tb_alerts.idStatusKf')
        ->join('tb_companies','tb_companies.idCompany', '=', 'tb_alerts.idCompanyKf')
        ->where('idStatus','!=','3')
        ->get();
        
        foreach($Alerts as $alert){
           if($alert->event==3){ 
            //    var_dump($alert->idCompanyKf);
                $Remitos = Remitos::join('tb_status','tb_status.idStatus', '=', 'tb_remitos_header.idStatusKf')
                ->join('tb_companies','tb_remitos_header.idCompanyProvider', '=', 'tb_companies.idCompany')
                ->where('idRemitosHeader','!=','')
                ->where('tb_remitos_header.idStatusKf','!=',3)
                ->where('tb_remitos_header.alert','!=',1)
                ->where('tb_remitos_header.idCompany','=',$alert->idCompanyKf)
                ->get();

                foreach($Remitos as $remito){
                        $email=$alert->email;
                        $data = [
                        'fecha' => $remito->created_at  
                        ];
                        Mail::send('Mail.email',$data, function($msj) use ($email){
                            $msj->subject('Notificamos que se importo un remito en repostock');
                            $msj->to($email);
                        });
            
                    $remito = Remitos::where('numberRemito','=',$remito->numberRemito)->first();
                    $remito->alert  = 1;
                    $remito->save();
                }
            }

        }

        $historial = HistorialJobs::create([
            'nameJob'    => 'Proceso de envio de correos'
        ]);
        
    }
}
