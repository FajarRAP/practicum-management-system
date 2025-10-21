<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AnswerController extends Controller
{
    public function index(Request $request, Questionnaire $questionnaire)
    {
        Gate::authorize('view', Answer::class);

        $questionnaire->load('questions');
        $questionIds = $questionnaire->questions->pluck('id');

        $answers = Answer::where('user_id', $request->user()->id)
            ->whereIn('question_id', $questionIds)
            ->get()
            ->keyBy('question_id');

        return view('questionnaire.answer', [
            'questionnaire' => $questionnaire,
            'answers' => $answers,
        ]);
    }

    public function create(Request $request, Questionnaire $questionnaire)
    {
        Gate::authorize('view', Answer::class);

        return view('questionnaire.fill', compact('questionnaire'));
    }

    public function store(Request $request, Questionnaire $questionnaire)
    {
        Gate::authorize('create', Answer::class);

        $validated = $request->validate([
            'questionnaire_id' => ['required', 'exists:questionnaires,id'],
            'answers' => ['required', 'array'],
            'answers.*' => ['required'],
        ]);

        $user = $request->user();
        $questionIdsInRequest = array_keys($validated['answers']);

        // Validasi tambahan: Pastikan semua pertanyaan yang dijawab memang milik kuesioner ini
        $validQuestionCount = $questionnaire->questions()->whereIn('id', $questionIdsInRequest)->count();
        if ($validQuestionCount !== count($questionIdsInRequest)) {
            return back()->with('error', 'Invalid question submitted.');
        }

        // Mencegah pengiriman ganda: Cek apakah user sudah pernah menjawab salah satu pertanyaan di kuesioner ini
        $alreadySubmitted = Answer::where('user_id', $user->id)
            ->whereIn('question_id', $questionIdsInRequest)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()
                ->route('answer.create', $questionnaire)
                ->with('error', 'You have already submitted this questionnaire.');
        }


        // Gunakan transaksi database untuk memastikan semua jawaban tersimpan atau tidak sama sekali
        DB::transaction(function () use ($validated, $user) {
            foreach ($validated['answers'] as $questionId => $content) {
                Answer::create([
                    'user_id' => $user->id,
                    'question_id' => $questionId,
                    'content' => is_array($content) ? json_encode($content) : $content,
                ]);
            }
        });

        return redirect()
            ->route('answer.index', $questionnaire)
            ->with('success', 'Thank you! Your answers have been submitted successfully.');
    }
}
