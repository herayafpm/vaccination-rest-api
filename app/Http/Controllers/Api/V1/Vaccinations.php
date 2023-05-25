<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Society;
use App\Models\Vaccination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Vaccinations extends Controller
{
    public function get_data(Request $request)
    {
        $society = Society::where(['login_tokens' => $request->query('token')])->first();
        $vaccinations = Vaccination::where(['society_id' => $society->id])->orderBy('date', 'asc')->get()->all();
        $results = [];
        $no = 1;
        foreach ($vaccinations as $vaccine) {
            $text = ($no == 1) ? 'first' : 'second';
            $results[$text] = [
                'queue' => $vaccine->queue,
                'dose' => $vaccine->dose,
                'vaccination_date' => date("M d, Y", strtotime($vaccine->date)),
                'spot' => $vaccine->spot->load('regional'),
                'status' => $vaccine->status,
                'vaccine' => $vaccine->vaccine,
                'vaccinator' => $vaccine->vaccinator
            ];
            $no++;
        }
        return response()->json([
            'vaccination' => $results
        ], 200);
    }
    public function registrasi(Request $request)
    {
        $society = Society::where(['login_tokens' => $request->query('token')])->first();
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|date_format:Y-m-d',
            'spot_id' => 'required'
        ], [], [
            'spot_id' => 'spot'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Field',
                'errors' => $validator->errors()
            ], 401);
        }
        $cek_consultation = Consultation::where(['society_id' => $society->id, 'status' => 'accepted'])->where('doctor_id', '!=', null)->first();
        if (!$cek_consultation) {
            return response()->json([
                'message' => 'Yout consultation must be accepted by doctor before'
            ], 401);
        }
        $cek_vaccine = Vaccination::where(['society_id' => $society->id])->orderBy('date', 'asc')->first();
        if (!$cek_vaccine) {
            $last_queue = Vaccination::where(['date' => $request->date, 'spot_id' => $request->spot_id])->orderBy('queue', 'desc')->first();
            $queue = 1;
            if ($last_queue) {
                $queue = (int) $last_queue->queue + 1;
            }
            $vaccine = new Vaccination([
                'date' => $request->date,
                'spot_id' => $request->spot_id,
                'society_id' => $society->id,
                'queue' => $queue
            ]);
            $vaccine->save();
            return response()->json([
                'message' => 'First vaccination registered successful'
            ], 200);
        } else {
            $count_vaccine = Vaccination::where(['society_id' => $society->id])->orderBy('date', 'asc')->count();
            if ($count_vaccine >= 2) {
                return response()->json([
                    'message' => 'Society has been 2x vaccinated'
                ], 401);
            }
            $firstdate_vaccine = new Carbon($cek_vaccine->date);
            $secondate_vaccine = new Carbon($request->date);
            $diff = $firstdate_vaccine->diffInDays($secondate_vaccine);
            if ($diff < 30) {
                return response()->json([
                    'message' => 'Wait at least +30 days from 1st vaccination'
                ], 401);
            }
            $last_queue = Vaccination::where(['date' => $request->date, 'spot_id' => $request->spot_id])->orderBy('queue', 'desc')->first();
            $queue = 1;
            if ($last_queue) {
                $queue = (int) $last_queue->queue + 1;
            }
            $vaccine = new Vaccination([
                'date' => $request->date,
                'spot_id' => $request->spot_id,
                'society_id' => $society->id,
                'queue' => $queue
            ]);
            $vaccine->save();
            return response()->json([
                'message' => 'Second vaccination registered successful'
            ], 200);
        }
    }
}
