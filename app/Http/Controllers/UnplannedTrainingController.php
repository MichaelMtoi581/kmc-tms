<?php

namespace App\Http\Controllers;

use App\Imports\UnplannedTrainingImport;
use App\Models\Department;
use App\Models\FinancialYear;
use App\Models\FundingSource;
use App\Models\Staff;
use App\Models\TrainingCategory;
use App\Models\TrainingInstitution;
use App\Models\UnplannedTraining;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UnplannedTrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = UnplannedTraining::with([
            'staff', 'department', 'financialYear',
            'trainingCategory', 'trainingInstitution', 'fundingSource',
        ]);

        if ($request->filled('financial_year_id')) {
            $query->where('financial_year_id', $request->financial_year_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trainings = $query->latest()->get();

        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        $departments = Department::orderBy('name')->get();

        return view('unplanned-trainings.index', compact(
            'trainings', 'financialYears', 'departments'
        ));
    }

    public function create()
    {
        $staff = Staff::orderBy('first_name')->get();
        $departments = Department::orderBy('name')->get();
        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        $categories = TrainingCategory::orderBy('name')->get();
        $institutions = TrainingInstitution::orderBy('name')->get();
        $fundingSources = FundingSource::orderBy('name')->get();

        return view('unplanned-trainings.create', compact(
            'staff', 'departments', 'financialYears',
            'categories', 'institutions', 'fundingSources'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_title' => 'required|string|max:255',
            'staff_id' => 'required|exists:staff,id',
            'department_id' => 'required|exists:departments,id',
            'financial_year_id' => 'required|exists:financial_years,id',
            'training_category_id' => 'required|exists:training_categories,id',
            'training_institution_id' => 'nullable|exists:training_institutions,id',
            'funding_source_id' => 'nullable|exists:funding_sources,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:Planned,Ongoing,Completed,Cancelled',
            'description' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $validated['cost'] = $request->cost ?? 0;
        $validated['source'] = 'manual';

        UnplannedTraining::create($validated);

        return redirect()
            ->route('unplanned-trainings.index')
            ->with('success', 'Unplanned training added successfully');
    }

    public function show(UnplannedTraining $unplannedTraining)
    {
        $unplannedTraining->load([
            'staff', 'department', 'financialYear',
            'trainingCategory', 'trainingInstitution', 'fundingSource',
        ]);

        return view('unplanned-trainings.show', compact('unplannedTraining'));
    }

    public function edit(UnplannedTraining $unplannedTraining)
    {
        $staff = Staff::orderBy('first_name')->get();
        $departments = Department::orderBy('name')->get();
        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        $categories = TrainingCategory::orderBy('name')->get();
        $institutions = TrainingInstitution::orderBy('name')->get();
        $fundingSources = FundingSource::orderBy('name')->get();

        return view('unplanned-trainings.edit', compact(
            'unplannedTraining', 'staff', 'departments', 'financialYears',
            'categories', 'institutions', 'fundingSources'
        ));
    }

    public function update(Request $request, UnplannedTraining $unplannedTraining)
    {
        $validated = $request->validate([
            'course_title' => 'required|string|max:255',
            'staff_id' => 'required|exists:staff,id',
            'department_id' => 'required|exists:departments,id',
            'financial_year_id' => 'required|exists:financial_years,id',
            'training_category_id' => 'required|exists:training_categories,id',
            'training_institution_id' => 'nullable|exists:training_institutions,id',
            'funding_source_id' => 'nullable|exists:funding_sources,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:Planned,Ongoing,Completed,Cancelled',
            'description' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $validated['cost'] = $request->cost ?? 0;

        $unplannedTraining->update($validated);

        return redirect()
            ->route('unplanned-trainings.index')
            ->with('success', 'Unplanned training updated successfully');
    }

    public function destroy(UnplannedTraining $unplannedTraining)
    {
        $unplannedTraining->delete();

        return redirect()
            ->route('unplanned-trainings.index')
            ->with('success', 'Unplanned training deleted successfully');
    }

    public function importForm()
    {
        return view('unplanned-trainings.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        Excel::import(new UnplannedTrainingImport, $request->file('file'));

        return redirect()
            ->route('unplanned-trainings.index')
            ->with('success', 'Unplanned trainings imported successfully');
    }
}
