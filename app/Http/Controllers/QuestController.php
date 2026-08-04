<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestRequest;
use App\Http\Requests\UpdateQuestRequest;
use App\Http\Resources\QuestResource;
use App\Models\Quest;
use App\Models\QuestCompletion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;





use function Illuminate\Support\now;

class QuestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {   
        $user = $request->user();
        return QuestResource::collection($user->quests()->withTodayCompletion()->get());
    }

    public function store(StoreQuestRequest $request)
    {
        $quest = Quest::create([...$request->validated(), 'user_id' => $request->user()->id]);

        return (new QuestResource($quest))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Quest $quest, Request $request)
    {
        $this->authorize('view', $quest);

        return new QuestResource($quest);
    }

    public function update(UpdateQuestRequest $request, Quest $quest)
    {
        $this->authorize('update', $quest);

        $quest->update($request->validated());
        return new QuestResource($quest);
    }

    public function destroy(Quest $quest, Request $request)
    {
        $this->authorize('delete', $quest);

        $quest->delete();

        return response()->json([
            'message' => "Quest deleted"
        ]);
    }

    public function complete(Quest $quest)
    {
        $this->authorize('update', $quest);

        $isCompletedToday = QuestCompletion::where('quest_id', '=', $quest->id)
            ->where('completed_on', '=', today())
            ->exists();

        if($isCompletedToday)
        {
            return response()->json([
                'message' => 'Quest already completed',
            ], 409);
        }

        $completion = QuestCompletion::create([
            'quest_id' => $quest->id,
            'completed_on' => today(),
            'completed_at' => now()
        ]);

        return response()->json([
            'message' => 'Quest completed!',
            'data' => $completion
        ]);
    }

    public function uncomplete(Quest $quest)
    {
        $this->authorize('update', $quest);
        
        if(!$quest->isCompletedToday(today()))
        {   
            return response()->json([
                'message' => 'Quest not completed today',
            ], 409); 
        }

        $quest->completions()->where('completed_on', '=', today())->delete();

        return response()->json([
            'message' => 'Quest uncomplete'
        ]);   
    }

    public function getStreak(Quest $quest)
    {
        return response()->json([
            'message' => 'Sucess getting streak',
            'data' => $quest->currentStreak()
        ], 200);
    }
}
