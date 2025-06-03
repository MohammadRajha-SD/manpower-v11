<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::find(1) ?? null;

        return view('admins.settings.terms.index', compact('terms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'terms' => 'required',
        ]);

        Term::updateOrCreate(
            ['id' => $id],
            ['content' => $request->terms]
        );

        return redirect()->back()->with('success', 'Terms Updated successfully!');
    }
}
