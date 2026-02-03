<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;

class FoglioServizioController extends Controller
{
    public function __invoke(Activity $activity)
    {
        $activity->load(['volunteers', 'vehicles', 'activityType', 'requestSource']);

        $day = $activity->date_from?->format('d/m/Y') ?? now()->format('d/m/Y');

        $activity->load([
    'volunteers.base',
    'vehicles.base',
    'activityType',
    'requestSource',
]);

        $pdf = Pdf::loadView('pdf.foglio-servizio', [
            'activity' => $activity,
            'day' => $day,
            'volunteers' => $activity->volunteers,
            'vehicles' => $activity->vehicles,
        ])->setPaper('a4', 'landscape'); // change to 'portrait' if needed

        $filename = 'foglio-servizio-activity-' . $activity->id . '.pdf';

        
        return $pdf->download($filename);
    }
}
