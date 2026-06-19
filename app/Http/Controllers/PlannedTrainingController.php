<?php

namespace App\Http\Controllers;

use App\Imports\PlannedTrainingImport;
use App\Models\Department;
use App\Models\FinancialYear;
use App\Models\FundingSource;
use App\Models\PlannedTraining;
use App\Models\Staff;
use App\Models\TrainingCategory;
use App\Models\TrainingInstitution;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PlannedTrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = PlannedTraining::with([
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

        return view('planned-trainings.index', compact(
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

        return view('planned-trainings.create', compact(
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

        PlannedTraining::create($validated);

        return redirect()
            ->route('planned-trainings.index')
            ->with('success', 'Planned training added successfully');
    }

    public function show(PlannedTraining $plannedTraining)
    {
        $plannedTraining->load([
            'staff', 'department', 'financialYear',
            'trainingCategory', 'trainingInstitution', 'fundingSource',
        ]);

        return view('planned-trainings.show', compact('plannedTraining'));
    }

    public function edit(PlannedTraining $plannedTraining)
    {
        $staff = Staff::orderBy('first_name')->get();
        $departments = Department::orderBy('name')->get();
        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        $categories = TrainingCategory::orderBy('name')->get();
        $institutions = TrainingInstitution::orderBy('name')->get();
        $fundingSources = FundingSource::orderBy('name')->get();

        return view('planned-trainings.edit', compact(
            'plannedTraining', 'staff', 'departments', 'financialYears',
            'categories', 'institutions', 'fundingSources'
        ));
    }

    public function update(Request $request, PlannedTraining $plannedTraining)
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

        $plannedTraining->update($validated);

        return redirect()
            ->route('planned-trainings.index')
            ->with('success', 'Planned training updated successfully');
    }

    public function destroy(PlannedTraining $plannedTraining)
    {
        $plannedTraining->delete();

        return redirect()
            ->route('planned-trainings.index')
            ->with('success', 'Planned training deleted successfully');
    }

    public function importForm()
    {
        return view('planned-trainings.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        Excel::import(new PlannedTrainingImport, $request->file('file'));

        return redirect()
            ->route('planned-trainings.index')
            ->with('success', 'Trainings imported successfully');
    }
}
