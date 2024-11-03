<?php

namespace App\Http\Controllers;

use App\Models\Muhud;
use App\Models\Person;
use App\Models\Shalawat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function information(): JsonResponse
    {
        try {

            $data = [];

            $sumMuhud = Muhud::count();
            $sumTextShalawat = Shalawat::count();
            $sumPimpinan = Person::count();
            $sumAudio = Person::count();

            $data['sumMuhud'] = $sumMuhud;
            $data['sumTextShalawat'] = $sumTextShalawat;
            $data['sumPimpinan'] = $sumPimpinan;
            $data['sumAudio'] = $sumAudio;

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aplikasi error.. ! Hubungi amar ' . $error->getMessage()
            ], 500);
        }
    }
}
