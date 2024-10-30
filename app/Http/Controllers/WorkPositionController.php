<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class WorkPositionController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->get('name');

        if(!!$name) {
            $positions = Position::where('name', 'like', "%$name%")->orWhere('name')->get();
        } 
        else {
            $positions = Position::all();        
        }
        return $this->successResponse(
            $this->paginate(
                collect(
                    $positions,
                )
                    ->toArray()
            )
        );
    }

    public function show($id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        return response()->json($position);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $position = Position::create($request->all());

        return response()->json($position, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'sometimes|required|string|max:255']);

        $position = Position::find($id);

        if (!$position) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        $position->update($request->all());

        return response()->json($position);
    }

    public function destroy($id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        $position->delete();

        return response()->json(['message' => 'Position deleted successfully']);
    }
}
