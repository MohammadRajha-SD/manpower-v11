<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index(Request $request)
    {

        $term = Term::find(1);

        $data = [
            'content' => $term->content ?? '',
        ];

        return response()->json($data, 200);
    }
}
