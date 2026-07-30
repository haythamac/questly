<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestRequest;
use App\Http\Requests\UpdateQuestRequest;
use App\Http\Resources\QuestResource;
use App\Models\Quest;
use App\Models\QuestCompletion;
use Illuminate\Http\Request;





use function Illuminate\Support\now;

class QuestController extends Controller
{
    public function index()
    {   
        return QuestResource::collection(Quest::withTodayCompletion()->get());
    }

    public function store(StoreQuestRequest $request)
    {
        $quest = Quest::create($request->validated());

        return (new QuestResource($quest))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Quest $quest)
    {
        return new QuestResource($quest);
    }

    public function update(UpdateQuestRequest $request, Quest $quest)
    {
        $quest->update($request->validated());
        return new QuestResource($quest);
    }

    public function destroy(Quest $quest)
    {
        $quest->delete();

        return response()->json([
            'message' => "Quest deleted"
        ]);
    }

    public function complete(Quest $quest)
    {
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

        if(!$quest->isCompletedToday())
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
}
