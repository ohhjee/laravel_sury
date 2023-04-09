<?php

namespace App\Http\Controllers\Survey;

use id;
use App\Enums\types;
use App\Models\Survey;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use App\Models\SurveyQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rules\Enum;
use App\Http\Resources\SuveryResource;
use App\Http\Requests\StoreSurveyRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateSurveyRequest;
use App\Http\Requests\storeSurveyAnswerRequest;
use App\Models\SurveyQuestionAnswer;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return SuveryResource::collection(Survey::where('user_id', $user->id)->paginate(6));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSurveyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveyRequest $request)
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }

        $survey = Survey::create($data);

        foreach ($data['questions'] as $question) {
            $question['survey_id'] = $survey->id;
            $this->createQuestion($question);
        }

        return new SuveryResource($survey);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey, Request $request)
    {
        $user = $request->user();
        if ($user->id !== $survey->user_id) {
            return abort(403, 'unauthorized action');
        }
        return new SuveryResource($survey);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function showForGuest(Survey $survey)
    {

        return new SuveryResource($survey);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function storeAnswer(storeSurveyAnswerRequest $request, Survey $survey)
    {
        $validated = $request->validated();
        $surveyAnswers = SurveyAnswer::create([
            'survey_id' => $survey->id,
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
        ]);
        foreach ($validated['answers'] as $questionId => $answer) {
            $question = SurveyQuestion::where(['id' => $questionId, 'survey_id' => $survey->id])->get();
            if (!$question) {
                return response("invalid question ID:\"$questionId\"", 400);
            }
            $data = [
                'survey_question_id' => $questionId,
                'survey_answer_id' => $surveyAnswers->id,
                'answer' => is_array($answer) ? json_encode($answer) : $answer,
            ];
            SurveyQuestionAnswer::create($data);
        };
        return response("", 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSurveyRequest  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {

        $data = $request->validated();
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
            if ($survey->image) {
                $absolute_path = public_path($survey->image);
                File::delete($absolute_path);
            }
        }
        var_dump($data);
        $survey->update($data);
        $existingIds = $survey->questions()->pluck('id')->toArray();

        $newIds = Arr::pluck($data['questions'], 'id');

        $toDelete = array_diff($existingIds, $newIds);

        $toAdd = array_diff($newIds, $existingIds);

        SurveyQuestion::destroy($toDelete);

        foreach ($data['questions'] as $question) {
            if (in_array($question['id'], $toAdd)) {
                $question['survey_id'] = $survey->id;
                $this->createQuestion($question);
            }
        }

        $questionMap = collect($data['questions'])->keyBy('id');

        foreach ($survey->questions as $question) {
            if (isset($questionMap[$question->id])) {
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }

        return new SuveryResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Survey $survey)
    {
        var_dump($survey);
        // $user = $request->user();
        // if ($user->id !== $survey->user_id) {
        //     return abort(403, 'unauthorized action');
        // };
        // $survey->delete();
        // if ($survey->image) {
        //     $absolute_path = public_path($survey->image);
        //     File::delete($absolute_path);
        // }
        // return response('', 204);
    }

    private function saveImage($image)
    {


        if (preg_match('/^data:image\/(\w+);base64,/', $image, $types)) {
            $image = substr($image, strpos($image, ',') + 1);
            $types = strtolower($types[1]);
            if (!in_array($types, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            };
            $image = str_replace('', '+', $image);
            $image = base64_decode($image);
            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match URL with image data');
        }
        $dir = 'images/';
        $file = Str::random() . '.' . $types;
        $absolute_path = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolute_path)) {
            File::makeDirectory($absolute_path, 0755, true);
        }
        file_put_contents($relativePath, $image);
        return $relativePath;
    }
    private function createQuestion($data)
    {
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }
        $validator = Validator::make($data, [
            'question' => 'required|string',
            'type' => ['required', new Enum(types::class)],
            'description' => 'nullable|string',
            'data' => 'present',
            'survey_id' => 'exists:App\models\survey,id',
        ]);
        return SurveyQuestion::create($validator->validated());
    }

    private function updateQuestion(SurveyQuestion $question, $data)
    {
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }
        $validator = Validator::make($data, [
            'id' => 'exists:App\Models\SurveyQuestion,id',
            'question' => 'required|string',
            'type' => ['required', new Enum(types::class)],
            'description' => 'nullable|string',
            'data' => 'present',
        ]);
        return $question->update($validator->validated());
    }
}
