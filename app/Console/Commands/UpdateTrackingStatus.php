<?php

namespace App\Console\Commands;

use App\Models\Track;
use App\Support\Getaway\Correios;
use Illuminate\Console\Command;

class UpdateTrackingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:trackingStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tracking status of each tracking code.';

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
     * @return int
     */
    public function handle()
    {
        $tracks = Track::get();
        $correios = new Correios();

        foreach ($tracks as $track) {
            $data = $correios->getDataWebServer($track->tracking_number);
            Track::find($track->id)->update($data);

            foreach ($data['events'] as $event) {
                $track->events()->updateOrCreate([
                    'track_id' => $track->id,
                    'date_event' => $event->dtHrCriado,
                    'event' => $event->descricao,
                    'unit' => json_encode($event->unidade)
                ]);
            }
        }

        return 0;
    }
}
