<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Remitos;
use App\RemitosBody;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\HistorialJobs;

class ChangeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remitos:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cambio de estatus a los remitos';

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
        $date = Carbon::now();
        $endDate = $date->subYears(1);
        $remitos= Remitos::where('created_at','<',$endDate)
        ->get();
        foreach($remitos as $remito){
                $remito = Remitos::find($remito->idRemitosHeader);
                $remito->idStatusKf  = 4;
                $remito->save(); 
                $remitoBody = RemitosBody::where('idHeaderRemitoKf', '=',$remito->idRemitosHeader)->get(); 

                foreach($remitoBody as $remit){
                    $remitos = RemitosBody::find($remit->idRemitosBody);
                    $remitos->idStatusKf  = 4;
                    $remitos->save(); 
                }
        }
        $remitosm= Remitos::where('created_at','>',$endDate)
        ->get();
        foreach($remitosm as $remitom){
                $remitom = Remitos::find($remitom->idRemitosHeader);
                $remitom->idStatusKf  = 6;
                $remitom->save(); 
                $remitoBodym = RemitosBody::where('idHeaderRemitoKf', '=',$remitom->idRemitosHeader)->get(); 

                foreach($remitoBodym as $remitm){
                    $remitosm = RemitosBody::find($remitm->idRemitosBody);
                    $remitosm->idStatusKf  = 6;
                    $remitosm->save(); 
                }
        }
        $historial = HistorialJobs::create([
            'nameJob'    => 'Cambio de estatus de remitos por fecha'
        ]);
    }
}
