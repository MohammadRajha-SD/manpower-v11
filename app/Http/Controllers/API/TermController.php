<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {

        $term = Term::find(1);

        $data = [
            'content' => $term->content ?? '',
        ];

        return response()->json($data, 200);
    }
}
