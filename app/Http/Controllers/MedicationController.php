<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicationRequest;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MedicationController extends Controller
{
    use ApiResponse;

    /**
     * store user medication and validate
     *
     * @param MedicationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MedicationRequest $request)
    {
        try {
            $rxcui = $request->rxcui;
    
            $validationUrl = 'https://rxnav.nlm.nih.gov/REST/rxcui/' . $rxcui . '/historystatus.json';
    
            $response = Http::get($validationUrl);
            if (!isset($response['rxcuiStatusHistory'])) {
                return $this->errorResponse([],'Invalid provided rxcui.',$request->input('rxcui'));
            }
            Medication::firstOrCreate([
                'user_id' => $request->user()->id,
                'rxcui' => $rxcui
            ]);
    
            return $this->successResponse([],'User Medication added.');
        } catch (\Throwable $ex) {
            Log::error("Error store user medication: " . $ex->getMessage());
            return $this->errorResponse([],'Something went wrong');
        }
    }

    /**
     * get user medication list
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $drugs = $request->user()->medications;
        try {
            return $drugs->map(function ($med) {
                $cacheKey = 'rxcui_status_' . $med->rxcui;
                    $statusData = Cache::remember($cacheKey, 1440, function () use ($med) {
                        $statusUrl = 'https://rxnav.nlm.nih.gov/REST/rxcui/' . $med->rxcui . '/historystatus.json';
                        $statusRes = Http::get($statusUrl);
    
                        if (!$statusRes->successful()) {
                            throw new \Exception('Failed to fetch drug status');
                        }
    
                        return $statusRes['rxcuiStatusHistory'] ?? [];
                    });
    
                return [
                    'rxcui' => $med->rxcui,
                    'drug_name' => $statusData['attributes']['name'] ?? null,
                    'baseNames' => array_column($statusData['definitionalFeatures']['ingredientAndStrength'] ?? [], 'baseName'),
                    'dose_forms_group' => array_column($statusData['definitionalFeatures']['doseFormGroupConcept'] ?? [], 'doseFormGroupName'),
                ];
            });
        } catch (\Throwable $ex) {
            Log::error("Error fetching user medication: " . $ex->getMessage());
            return $this->errorResponse([],'Something went wrong');
        }
    }

    /**
     * Delete user medication
     *
     * @param Request $request
     * @param string $rxcui
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $rxcui)
    {
        try {
            $deleted = Medication::where('user_id', $request->user()->id)->where('rxcui', $rxcui)->delete();
    
            if (!$deleted) {
                return $this->errorResponse([],'User Medication not found.',$request->input('rxcui'));
            }
            return $this->successResponse([],'User Medication deleted successfully.');
        } catch (\Throwable $ex) {
            Log::error("Error user medication delete: " . $ex->getMessage());
            return $this->errorResponse([],'Something went wrong');
        }
    }

}
