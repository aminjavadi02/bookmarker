<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\bookmarksModel;
use App\Jobs\FetchPageData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class BookmarksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return bookmarksModel::whereNull('deleted_at')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make(data: $request->all(), rules: [
            'url' => 'required|url|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(data: [
                'message' => 'Invalid URL format',
                'errors' => $validator->errors(),
            ], status: 422);
        }

        // Check for duplicates
        $existingBookmark = BookmarksModel::where('url', $request->url)->first();
        if ($existingBookmark) {
            return response()->json([
                'message' => 'This bookmark already exists.'
            ], status: 409);
        }

        $bookmark = BookmarksModel::create(['url' => $request->url]);

        FetchPageData::dispatch($bookmark);

        return response()->json($bookmark, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Validate ID format (UUID)
            if (!preg_match('/^[0-9a-fA-F-]{36}$/', $id)) {
                return response()->json([
                    'message' => 'Invalid uuid format.'
                ], status: 400);
            }

            $bookmark = BookmarksModel::where('id', $id)->firstOrFail();
            $bookmark->delete();

            return response()->json([
                'message' => 'Bookmark deleted successfully.'
            ], status: 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Bookmark not found.'
            ], status: 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], status: 500);
        }
    }
}
