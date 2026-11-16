<?php

namespace App\Services;

use App\Models\TalkShow;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

class TalkShowPdfService
{
    /**
     * Generate the signed application PDF and store it on the private
     * local disk (never publicly addressable). Returns the storage path.
     */
    public function store(TalkShow $application): string
    {
        $html = view('pdfs.talk-show-signed', [
            'application' => $application,
        ])->render();

        $dompdf = new Dompdf;
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $path = 'talk-show/'.$application->id.'-signed.pdf';
        Storage::disk('local')->put($path, $dompdf->output());

        return $path;
    }
}
