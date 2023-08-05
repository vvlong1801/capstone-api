<?php

namespace App\Http\Controllers\Admin\sources;

use App\Enums\TypeTag;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Http\Resources\Admin\TagResource;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::with('createdBy')->get();
        return $this->responseOk(TagResource::collection($tags), 'get tag success');
    }

    public function getExerciseTags()
    {
        $exeTags = Tag::with('createdBy')->where('type', TypeTag::GroupExercise)->get();
        return $this->responseOk(TagResource::collection($exeTags), 'get exercise tag success');
    }

    public function getChallengeTags()
    {
        $exeTags = Tag::with('createdBy')->where('type', TypeTag::ChallengeTag)->get();
        return $this->responseOk(TagResource::collection($exeTags), 'get exercise tag success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $payload = $request->validated();

        try {
            $payload['type'] = TypeTag::fromNameToEnum($payload['type']);
            $payload['created_by'] = Auth::user()->id;
            DB::beginTransaction();
            Tag::createOrIgnore($payload['type'], [$payload]);
            DB::commit();
            return $this->responseNoContent("create tag success");
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, "can't create tag");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Tag::destroy($id);
        return $this->responseNoContent('tag has been deleted');
    }
}
