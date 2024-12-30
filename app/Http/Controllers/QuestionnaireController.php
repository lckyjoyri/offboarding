<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questions = Question::all();
        return view('pages.hr.questionnaire.questionnaire.index', compact('questions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
        $request->all(), [
            'question' => 'required|string|max:255',
        ]);

        if($validator->fails())
        {
            \Log::error('Validation failed', [
                'errors' => $validator->errors(),
                'input' => $request->all()
            ]);

            return redirect()->back()->with('error', ' Something went wrong!.');
        }

        $data = $request->all();

        try {
            // Log before creating the Question
        \Log::info('Attempting to create question', [
            'question_data' => ['question' => $data['question']]
        ]);
            Question::create(['question' => $data['question'],]);

            return redirect()->back()->with('success', 'Question saved successfuly');

        } catch (\Exception $e) {
            \Log::error('Error saving clearance request', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            
            return redirect()->back()->with('error', 'There was an issue saving your question');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(), [
                'question' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Something went wrong!.');
        }

        try {
            // Find the existing question by ID
            $question = Question::findOrFail($id);
            
            // Update the question's text
            $question->update(['question' => $request->question]);

            return redirect()->back()->with('success', 'Question updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating question', ['error_message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'There was an issue updating your request');
        }
    }
    public function delete($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();
    
            return redirect()->back()->with('success', 'Question deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Error deleting question', ['error_message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'There was an issue deleting the question');
        }
    }
}
