<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use Illuminate\Http\Request;

class FinancialYearController extends Controller
{
    public function index()
    {
        $years = FinancialYear::orderBy('year_name', 'desc')->get();
        $totalYears = $years->count();
        $activeYears = $years->where('is_active', true)->count();

        return view('financial-years.index', compact('years', 'totalYears', 'activeYears'));
    }

    public function create()
    {
        return view('financial-years.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year_name' => 'required|unique:financial_years',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        FinancialYear::create([
            'year_name' => $request->year_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? 0,
        ]);

        return redirect()
            ->route('financial-years.index')
            ->with('success', 'Financial Year Added Successfully');
    }

    public function edit(FinancialYear $financialYear)
    {
        return view('financial-years.edit', compact('financialYear'));
    }

    public function update(Request $request, FinancialYear $financialYear)
    {
        $request->validate([
            'year_name' => 'required|unique:financial_years,year_name,' . $financialYear->id,
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $financialYear->update($request->only('year_name', 'start_date', 'end_date', 'is_active'));

        return redirect()
            ->route('financial-years.index')
            ->with('success', 'Updated Successfully');
    }

    public function destroy(FinancialYear $financialYear)
    {
        try {
            $financialYear->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()
                    ->route('financial-years.index')
                    ->with('error', "Cannot delete \"{$financialYear->year_name}\" — it has training records attached.");
            }
            throw $e;
        }

        return redirect()
            ->route('financial-years.index')
            ->with('success', 'Deleted Successfully');
    }
}