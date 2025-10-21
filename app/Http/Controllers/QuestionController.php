<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Questionnaire $questionnaire)
    {
        $questionnaire->load('questions');

        return view('questionnaire.manage-questions', [
            'questionnaire' => $questionnaire,
        ]);
    }

    public function store(Request $request, Questionnaire $questionnaire)
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
            'type' => ['required', 'in:radio,checkbox,text'],
            'options' => ['required_if:type,radio,checkbox', 'array'],
            'options.*' => ['nullable', 'string'],
        ]);

        $isTextType = $request->type === 'text';

        $validated['options'] = $isTextType ? null : json_encode(array_filter($validated['options']));
        $validated['questionnaire_id'] = $questionnaire->id;

        Question::create($validated);

        return back()->with('success', 'Question added successfully.');
    }

    public function update(Request $request, Question $question)
    {
        // Otorisasi
        // $this->authorize('update', $question);

        $validated = $request->validate([
            'content' => ['required', 'string'],
            'type' => ['required', 'in:radio,checkbox,text'],
            'options' => ['required_if:type,radio,checkbox', 'array'],
            'options.*' => ['nullable', 'string'],
        ]);

        $isTextType = $request->type === 'text';

        $validated['options'] = $isTextType ? null : json_encode(array_filter($validated['options']));
        $question->update($validated);

        return back()->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        try {
            $question->delete();
            return back()->with('success', 'Question deleted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to delete question.');
        }
    }
}
