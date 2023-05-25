<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Society;
use Illuminate\Http\Request;

class Consultations extends Controller
{
    public function consultations(Request $request)
    {
        $society = Society::where(['login_tokens' => $request->query('token')])->first();
        $consultation = Consultation::where(['society_id' => $society->id])->first();
        $doctor = null;
        $res = [];
        if($consultation){
            $doctor = $consultation->doctor;
            $res = [
                'id' => $consultation->id,
                'status' => $consultation->status,
                'disease_history' => $consultation->disease_history,
                'current_symptoms' => $consultation->current_symptoms,
                'doctor_notes' => $consultation->doctor_notes,
                'doctor' => $doctor ?? null
            ];
        }

        return response()->json([
            'consultation' => $res
        ],200);
    }
    public function request_consultations(Request $request)
    {
        $society = Society::where(['login_tokens' => $request->query('token')])->first();
        $consul = new Consultation([
            'society_id' => $society->id,
            'disease_history' => $request->disease_history ?? "",
            'current_symptoms' => $request->current_symptoms ?? "",
        ]);
        if($consul->save()){
            return response()->json([
                'message' => 'Request consultation sent successful'  
            ],200);
        }else{
            return response()->json([
                'message' => 'Request consultation sent failed'  
            ],401);
        }
    }
}
