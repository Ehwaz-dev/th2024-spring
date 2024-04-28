<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlacesController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->query('q');

        $regions = City::query()
            ->selectRaw("region, region_type, similarity(region, ?) AS sml", [$q])
            ->groupBy('region', 'region_type')
            ->where('region', 'ilike', $q.'%')
            ->get();

        $regions = $regions->map(fn($i) => [
            'name' => $i['region'] . ' ' . $i['region_type'],
            'name_id' => $i['region'],
            'sml' => $i['sml'],
            'type' => 'region'
        ]);

        $cities = City::query()
            ->select('city')
            ->selectRaw("city, similarity(city, ?) AS sml", [$q])
            ->where('city', 'ilike', $q.'%')
            ->get();

        $cities = $cities->map(fn($i) => [
            'name' => $i['city'],
            'name_id' => $i['city'],
            'sml' => $i['sml'],
            'type' => 'city'
        ]);

        // ПИПЕЦ ГОВНО
        $res = collect()
            ->merge($regions)
            ->merge($cities)
            ->sortByDesc('sml')
            ->select(['name', 'name_id', 'type']);

        return JsonResource::collection($res);
    }
}
