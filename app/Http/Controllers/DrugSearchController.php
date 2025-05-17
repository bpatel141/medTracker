<?php

namespace App\Http\Controllers;

use App\Http\Requests\DrugSearchRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponse;


class DrugSearchController extends Controller
{
    use ApiResponse;
    /**
     * Search for drugs by name using the RxNorm API.
     *
     * @param  \App\Http\Requests\DrugSearchRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(DrugSearchRequest $request)
    {
        $drugName = $request->input('drug_name');
        $cacheKey = 'drug_' . strtolower($drugName);
        try {
            $results = Cache::remember($cacheKey, 3600, function () use ($drugName,$request) {
                $getDrugsUrl = 'https://rxnav.nlm.nih.gov/REST/drugs.json?name=' . urlencode($drugName);
                $getDrugsRes = Http::get($getDrugsUrl);
    
                if (!$getDrugsRes->successful()) {
                    return $this->errorResponse([],'Something went wrong',$request->input('drug_name'));
                }
                $conceptGroup = $getDrugsRes['drugGroup']['conceptGroup'] ?? [];
                $sbdGroup = collect($conceptGroup)->firstWhere('tty', 'SBD');
                $conceptProperties = collect($sbdGroup['conceptProperties'] ?? []);
    
                return $conceptProperties->take(5)->map(function ($item) {
                    $rxcui = $item['rxcui'];
                    $name = $item['name'];
                    $statusUrl = 'https://rxnav.nlm.nih.gov/REST/rxcui/' . $rxcui . '/historystatus.json';
    
                    $statusRes = Http::get($statusUrl);
                    $statusData = $statusRes['rxcuiStatusHistory'] ?? [];
    
                    $ingredients = array_column($statusData['definitionalFeatures']['ingredientAndStrength'] ?? [], 'baseName');
                    $doseForms = array_column($statusData['definitionalFeatures']['doseFormGroupConcept'] ?? [], 'doseFormGroupName');
                        
                    return [
                        'rxcui' => $rxcui,
                        'drug_name' => $name,
                        'base_ingredients' => $ingredients,
                        'dose_forms' => $doseForms,
                    ];
                })->values()->toArray();
            });
            return $this->successResponse($results,'Success', $request->input('drug_name'));
        } catch (\Throwable $ex) {
             Log::error('Drug search error: ' . $ex->getMessage());
            return $this->errorResponse([],'Something went wrong',$request->input('drug_name'));
        }
    }
}
