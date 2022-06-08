<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\VoiceAuditsExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function voiceAudits(Request $request)
    {
        $now = now();
        return Excel::download(new VoiceAuditsExport($request), "Voice-Audits-{$now->toString()}.xlsx");
    }
}
