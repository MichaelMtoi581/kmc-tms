<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use Illuminate\Http\Request;

class FinancialYearController extends Controller
{
    public function index()
    {
        $years = FinancialYear::latest()->paginate(10);

        return view('financial-years.index', compact('years'));
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
            'year_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $financialYear->update($request->all());

        return redirect()
            ->route('financial-years.index')
            ->with('success', 'Updated Successfully');
    }

    public function destroy(FinancialYear $financialYear)
    {
        $financialYear->delete();

        return redirect()
            ->route('financial-years.index')
            ->with('success', 'Deleted Successfully');
    }
}