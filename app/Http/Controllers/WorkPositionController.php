<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Http\Resources\PositionResource;

class WorkPositionController extends Controller
{
    public function index(Request $request)
    {
        return PositionResource::collection(Position::paginate($request->get('per_page')));
    }

    public function show($id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json(['message' => 'Должность не найдена'], 404);
        }

        return (new PositionResource($position))->additional(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $position = Position::create($request->all());

        return (new PositionResource($position))->additional(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'sometimes|required|string|max:255']);

        $position = Position::find($id);

        if (!$position) {
            return response()->json(['message' => 'Должность не найдена'], 404);
        }

        $position->update($request->all());

        return (new PositionResource($position))->additional(['success' => true]);
    }

    public function destroy($id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json(['message' => 'Должность не найдена'], 404);
        }

        $position->delete();

        return response()->json(['message' => 'Должность удалена успешно'], 204);
    }
}
