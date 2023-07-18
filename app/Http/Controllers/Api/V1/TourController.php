<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Travel $travel, Request $request)
    {
        $tours = $travel->tours()
            ->when($request->priceFrom, function ($query) use ($request) {
                $query->where('price', '>= ', $request->priceFrom * 100);
            })
            ->when($request->priceTo, function ($query) use ($request) {
                $query->where('price', '<= ', $request->priceTo * 100);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                $query->where('starting_date', '>= ', $request->dateFom);
            })
            ->when($request->dateTo, function ($query) use ($request) {
                $query->where('starting_date', '<= ', $request->dateTo);
            })
            ->orderBy('starting_date', 'asc')->paginate();

        return TourResource::collection($tours);
    }
}
