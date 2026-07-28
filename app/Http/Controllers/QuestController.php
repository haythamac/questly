<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestRequest;
use App\Http\Requests\UpdateQuestRequest;
use App\Http\Resources\QuestResource;
use App\Models\Quest;
use Illuminate\Http\Request;

class QuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return QuestResource::collection(Quest::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestRequest $request)
    {
        $quest = Quest::create($request->validated());

        return (new QuestResource($quest))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quest $quest)
    {
        return new QuestResource($quest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestRequest $request, Quest $quest)
    {
        $quest->update($request->validated());
        return new QuestResource($quest);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quest $quest)
    {
        $quest->delete();

        return response()->json([
            'message' => "Quest deleted"
        ]);
    }
}
