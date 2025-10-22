<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuestionnaireController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Questionnaire::class);

        $questionnaires = Questionnaire::query()
            ->select('questionnaires.*')
            ->leftJoin('questions', 'questionnaires.id', '=', 'questions.questionnaire_id')
            ->leftJoin('answers', 'questions.id', '=', 'answers.question_id')
            ->groupBy('questionnaires.id')
            ->selectRaw('COUNT(DISTINCT answers.user_id) as respondents_count')
            ->latest('questionnaires.created_at')
            ->paginate(15);

        return view('questionnaire.index', compact('questionnaires'));
    }

    public function show(Questionnaire $questionnaire)
    {
        Gate::authorize('view', $questionnaire);

        $questions = $questionnaire->questions()->with('answers')->get();

        $totalResponses = $questionnaire->answers()->count();

        $results = $questions->map(function ($question) {
            $answers = $question->answers->pluck('content');

            if ($question->type === 'radio' || $question->type === 'checkbox') {
                $answers = $answers->flatMap(fn($json) => json_decode($json) ?? [$json]);
                $aggregated = $answers->countBy();
            } else {
                $aggregated = $answers;
            }

            return [
                'question' => $question,
                'answers' => $aggregated,
            ];
        });

        return view('questionnaire.results', [
            'questionnaire' => $questionnaire,
            'results' => $results,
            'totalResponses' => $totalResponses,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Questionnaire::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Logika untuk memastikan hanya ada satu kuesioner aktif
        if ($request->has('is_active')) {
            Questionnaire::where('is_active', true)->update(['is_active' => false]);
            $validated['is_active'] = true;
        } else {
            $validated['is_active'] = false;
        }

        Questionnaire::create($validated);

        return redirect()->route('questionnaire.index')->with('success', 'Questionnaire created successfully.');
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        Gate::authorize('update', $questionnaire);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($validated['is_active']) {
            Questionnaire::where('id', '!=', $questionnaire->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $questionnaire->update($validated);

        return redirect()->route('questionnaire.index')->with('success', 'Questionnaire updated successfully.');
    }

    public function destroy(Questionnaire $questionnaire)
    {
        Gate::authorize('delete', $questionnaire);

        try {
            $questionnaire->delete();
            return redirect()->route('questionnaire.index')->with('success', 'Questionnaire deleted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Error occurred when deleting Questionnaire.');
        }
    }
}
