<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Offense;
use Illuminate\Http\Request;

class OffenseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'offense_date' => 'nullable|date',
            'status'       => 'in:pending,resolved,dismissed',
        ]);

        $validated['filed_by'] = auth()->id();
        $validated['status']   = $validated['status'] ?? 'pending';

        Offense::create($validated);

        return back()->with('success', 'Offense record added.');
    }

    public function update(Request $request, Offense $offense)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'offense_date' => 'nullable|date',
            'status'       => 'required|in:pending,resolved,dismissed',
        ]);

        $offense->update($validated);

        return back()->with('success', 'Offense record updated.');
    }

    public function destroy(Offense $offense)
    {
        $offense->delete();
        return back()->with('success', 'Offense record deleted.');
    }
}