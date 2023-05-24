<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Society;
use App\Models\Spot;
use App\Models\SpotVaccine;
use App\Models\Vaccination;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class Spots extends Controller
{
    public function spot_vaccines(Request $request)
    {
        $society = Society::where(['login_tokens' => $request->query('token')])->first();
        $vaccines = Vaccine::all();
        return response()->json([
            'spots' => Spot::where(['regional_id' => $society->regional_id])->get()->map(function(Spot $spot) use ($vaccines){
                $spot_vaccines = [];
                foreach ($vaccines as $vaccine) {
                    $spot_vaccines[$vaccine->name] = SpotVaccine::where(['spot_id' => $spot->id,'vaccine_id' => $vaccine->id])->count() > 0;
                }
                return [
                    'id' => $spot->id,
                    'name' => $spot->name,
                    'address' => $spot->address,
                    'serve' => $spot->serve,
                    'capacity' => $spot->capacity,
                    'available_vaccines' => $spot_vaccines
                ];
            })
        ],200);
    }

    public function spot_detail(Request $request,$spot_id)
    {
        $tanggal = $request->query('date') ?? date("Y-m-d");
        $spot = Spot::where(['id' => $spot_id])->first();
        return response()->json([
            'date' => date("M d, Y", strtotime($tanggal)),
            'spot' => [
                'id' => $spot->id,
                'name' => $spot->name,
                'address' => $spot->address,
                'serve' => $spot->serve,
                'capacity' => $spot->capacity
            ],
            'vaccinations_count' => Vaccination::where(['spot_id' => $spot_id,'date' => $tanggal])->count(),
            ]);
    }
}
