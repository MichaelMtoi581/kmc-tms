<?php

namespace App\Http\Controllers;

use App\Models\FundingSource;
use Illuminate\Http\Request;

class FundingSourceController extends Controller
{
    public function index()
    {
        $sources = FundingSource::orderBy('name')->get();

        return view('funding-sources.index', compact('sources'));
    }

    public function create()
    {
        return view('funding-sources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:funding_sources,name',
            'description' => 'nullable|string',
        ]);

        FundingSource::create($validated);

        return redirect()
            ->route('funding-sources.index')
            ->with('success', 'Funding source added successfully');
    }

    public function show(FundingSource $fundingSource)
    {
        return redirect()->route('funding-sources.edit', $fundingSource);
    }

    public function edit(FundingSource $fundingSource)
    {
        return view('funding-sources.edit', ['source' => $fundingSource]);
    }

    public function update(Request $request, FundingSource $fundingSource)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:funding_sources,name,' . $fundingSource->id,
            'description' => 'nullable|string',
        ]);

        $fundingSource->update($validated);

        return redirect()
            ->route('funding-sources.index')
            ->with('success', 'Funding source updated successfully');
    }

    public function destroy(FundingSource $fundingSource)
    {
        $fundingSource->delete();

        return redirect()
            ->route('funding-sources.index')
            ->with('success', 'Funding source deleted successfully');
    }
}
