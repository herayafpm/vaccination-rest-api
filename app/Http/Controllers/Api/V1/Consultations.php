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
        return response()->json([
            'consultation' => Consultation::where(['society_id' => $society->id])->get()->map(function(Consultation $consul){
                $doctor = $consul->doctor;
                return [
                    'id' => $consul->id,
                    'status' => $consul->status,
                    'disease_history' => $consul->disease_history,
                    'current_symptoms' => $consul->current_symptoms,
                    'doctor_notes' => $consul->doctor_notes,
                    'doctor' => $doctor ?? null
                ];
            })
        ],200);
    }
    public function request_consultations(Request $request)
    {
        $society = Society::where(['login_tokens' => $request->query('token')])->first();
        $consul = new Consultation([
            'society_id' => $society->id,
            'disease_history' => $request->disease_history ?? null,
            'current_symptoms' => $request->current_symptoms ?? null,
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
