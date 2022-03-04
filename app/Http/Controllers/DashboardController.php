<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $countTracksGroup = Track::groupBy('status')
                            ->selectRaw('count(*) as total, status')
                            ->get();

        $totalTracks = Track::get()->count();

        $statusTypes = Track::getStatusTypes();

        return Inertia::render('Dashboard/DashboardIndex', [
            'countTracksGroup' => $countTracksGroup,
            'totalTracks' => $totalTracks,
            'statusTypes' => $statusTypes
        ]);
    }
}
