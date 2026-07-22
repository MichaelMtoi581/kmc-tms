<?php

namespace App\Http\Controllers;

use App\Imports\TnaImport;
use App\Models\AuditLog;
use App\Models\TnaExercise;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TnaImportController extends Controller
{
    public function form()
    {
        $exercises = TnaExercise::where('status', 'Open')
            ->with('financialYear')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tna.import.form', compact('exercises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tna_exercise_id' => 'required|exists:tna_exercises,id',
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $exercise = TnaExercise::findOrFail($request->tna_exercise_id);

        if (!$exercise->isOpen()) {
            return back()->with('error', 'This TNA exercise is Closed. You cannot import into a closed exercise.');
        }

        try {
            $import = new TnaImport($exercise->id);
            Excel::import($import, $request->file('file'));

            $imported = $import->rowsImported;
            $skipped = count($import->failures);
            $duplicates = $import->duplicatesSkipped;

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'imported',
                'model_type' => TnaExercise::class,
                'model_id' => $exercise->id,
                'changes' => ['imported' => $imported, 'duplicates' => $duplicates, 'errors' => $skipped],
                'description' => "Imported $imported TNA response(s) into exercise: {$exercise->title}" .
                    ($duplicates ? ", $duplicates duplicate(s) skipped" : '') .
                    ($skipped ? ", $skipped error(s)" : ''),
            ]);

            $parts = [];
            if ($imported > 0) $parts[] = "$imported response(s) imported";
            if ($duplicates > 0) $parts[] = "$duplicates duplicate(s) skipped";
            if ($skipped > 0) $parts[] = "$skipped row(s) with errors skipped";
            $summary = implode(', ', $parts) ?: 'No rows were imported';

            if ($skipped > 0 || $duplicates > 0) {
                $details = '';
                if ($duplicates > 0) $details .= "Duplicate rows (same check number + training in this exercise) were skipped.\n";
                if ($skipped > 0) {
                    $details .= collect($import->failures)
                        ->map(fn($f) => "Row {$f->row()}: " . implode(', ', $f->errors()))
                        ->join("\n");
                }
                return redirect()->route('tna.responses.index', ['tna_exercise_id' => $exercise->id])
                    ->with('warning', $summary . ':')
                    ->with('warning_details', $details);
            }

            return redirect()->route('tna.responses.index', ['tna_exercise_id' => $exercise->id])
                ->with('success', $summary);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())
                ->map(fn($f) => "Row {$f->row()}: " . implode(', ', $f->errors()))
                ->join("\n");
            return back()->with('import_errors', $failures);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('TNA import failed', [
                'error' => $e->getMessage(),
                'exercise' => $exercise->id,
            ]);
            return back()->with('error', 'Import failed. Please check your file format and try again.');
        }
    }

    public function downloadTemplate()
    {
        return \Excel::download(new \App\Exports\TnaTemplateExport, 'tna-import-template.xlsx');
    }
}
