<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyResourceDashboard;


class DashboardController extends Controller
{
    public function index(Request  $request)
    {
        $user = $request->user();
        $total = Survey::query()->where('user_id', $user->id)->count();
        $latest = Survey::query()->where('user_id', $user->id)->latest('created_at')->first();
        $totalAnswer = SurveyAnswer::query()->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')->where('surveys.user_id', $user->id)->count();
        $latestAnswer = SurveyAnswer::query()->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')->where('surveys.user_id', $user->id)->orderBy('end_date', 'DESC')->limit(5)->getModels('survey_answers.*');
        return [
            'totalSurveys' => $total,
            'totalAnswer' => $totalAnswer,
            'latestAnswer' => SurveyAnswerResource::collection($latestAnswer),
            'latestSurvey' => $latest ? new SurveyResourceDashboard($latest) : null
        ];
    }
}
