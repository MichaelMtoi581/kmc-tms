<?php

namespace App\Http\Controllers;

use App\Imports\UnplannedTrainingImport;
use App\Models\AuditLog;
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

        $existing = UnplannedTraining::where('staff_id', $validated['staff_id'])
            ->where('course_title', $validated['course_title'])
            ->where('financial_year_id', $validated['financial_year_id'])
            ->exists();

        if ($existing) {
            return back()->withInput()->with('error', 'This staff member is already registered for this course in the selected financial year.');
        }

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

        $existing = UnplannedTraining::where('staff_id', $validated['staff_id'])
            ->where('course_title', $validated['course_title'])
            ->where('financial_year_id', $validated['financial_year_id'])
            ->where('id', '!=', $unplannedTraining->id)
            ->exists();

        if ($existing) {
            return back()->withInput()->with('error', 'This staff member is already registered for this course in the selected financial year.');
        }

        $validated['cost'] = $request->cost ?? 0;

        $unplannedTraining->update($validated);

        return redirect()
            ->route('unplanned-trainings.index')
            ->with('success', 'Unplanned training updated successfully');
    }

    public function destroy(UnplannedTraining $unplannedTraining)
    {
        try {
            $unplannedTraining->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()
                    ->route('unplanned-trainings.index')
                    ->with('error', 'Cannot delete this training — it is referenced by other records.');
            }
            throw $e;
        }

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

        try {
            $import = new UnplannedTrainingImport;
            Excel::import($import, $request->file('file'));

            $imported = $import->rowsImported;
            $skipped = count($import->failures);
            $duplicates = $import->duplicatesSkipped;

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'imported',
                'model_type' => UnplannedTraining::class,
                'model_id' => null,
                'changes' => ['imported' => $imported, 'duplicates' => $duplicates, 'errors' => $skipped],
                'description' => "Imported $imported unplanned training(s)" . ($duplicates ? ", $duplicates duplicate(s) skipped" : '') . ($skipped ? ", $skipped error(s)" : ''),
            ]);

            $parts = [];
            if ($imported > 0) {
                $parts[] = "$imported training(s) imported";
            }
            if ($duplicates > 0) {
                $parts[] = "$duplicates duplicate(s) skipped";
            }
            if ($skipped > 0) {
                $parts[] = "$skipped row(s) with errors skipped";
            }
            $summary = implode(', ', $parts) ?: 'No rows were imported';

            if ($skipped > 0 || $duplicates > 0) {
                $details = '';
                if ($duplicates > 0) {
                    $details .= "Duplicate rows (same staff + course + financial year) were skipped.\n";
                }
                if ($skipped > 0) {
                    $details .= collect($import->failures)
                        ->map(fn($f) => "Row {$f->row()}: " . implode(', ', $f->errors()))
                        ->join("\n");
                }
                return redirect()
                    ->route('unplanned-trainings.index')
                    ->with('warning', $summary . ':')
                    ->with('warning_details', $details);
            }

            return redirect()
                ->route('unplanned-trainings.index')
                ->with('success', $summary);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())
                ->map(fn($f) => "Row {$f->row()}: " . implode(', ', $f->errors()))
                ->join("\n");
            return back()->with('import_errors', $failures);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Unplanned training import failed', [
                'error' => $e->getMessage(),
                'file' => $request->file('file')->getClientOriginalName(),
            ]);
            return back()->with('error', 'Import failed. Please check your file format and try again.');
        }
    }
}
