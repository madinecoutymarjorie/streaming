<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achat;

class AchatController extends Controller
{
     public function index()
    {
        return response()->json(Achat::all());
    }
}
