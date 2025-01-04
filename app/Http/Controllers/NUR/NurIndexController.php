<?php

namespace App\Http\Controllers\NUR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NurIndexController extends Controller
{
    public function index()
    {
        $weeks = [];
        $years = [];
        for ($i = 1; $i <= 52; $i++) {
            array_push($weeks, $i);
        }
        for ($i = 2022; $i <= 2050; $i++) {
            array_push($years, $i);
        }


        return response()->json([
            "weeks" => $weeks,
            "years" => $years


        ], 200);
    }

}
