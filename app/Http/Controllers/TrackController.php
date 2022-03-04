<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Support\Getaway\Correios;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TrackController extends Controller
{

    /**
     * Update all tracking codes status
     *
     * @return Response
     */
    public function updateTracks()
    {
        Artisan::call('update:trackingStatus');

        return redirect()->back()
                    ->with('message', 'Códigos Atualizados com Sucesso.');
    }

    /**
     * Show the list of resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tracks = Track::with('events')->get();

        $status = Track::getStatusTypes();

        return Inertia::render('Tracking/TracksIndex', [
            'tracks' => $tracks,
            'statusTypes' => $status
        ]);
    }

    /**
     * Create new resource
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $data = $request->all();

        Validator::make($request->all(), [
            'tracking_number' => 'required|unique:tracks,tracking_number,NULL,id,user_id,'.Auth::id(),
        ])->validate();

        $correios = new Correios();
        $data = $correios->getDataWebServer($data['tracking_number']);

        $events = $data['events'];
        unset($data['events']);

        $track = Track::create($data);

        foreach ($events as $event) {
            $track->events()->updateOrCreate([
                'track_id' => $track->id,
                'date_event' => $event->dtHrCriado,
                'event' => $event->descricao,
                'unit' => json_encode($event->unidade)
            ]);
        }

        return redirect()->back()
                    ->with('message', 'Código Cadastrado com Sucesso.');
    }

    /**
     * Destroy resource.
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $track = Track::find($id);

        if ($track) {
            $track->delete();
            return redirect()->back();
        }
    }


}
