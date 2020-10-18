<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TagController extends BaseController
{
    public function getUserTags($id)
    {
        $tags = Tag::where('user_id', $id)->get();
        $allTags = Tag::where('user_id', null)->get();

        if(!empty($tags)) {
            $tags = $tags->toArray();
        }
        if(!empty($allTags)) {
            $allTags = $allTags->toArray();
        }

        $userTags = array_merge($tags, $allTags);
        if(is_null($userTags)) {
            return $this->sendError('Теги не найдены.');
        }

        return $this->sendResponse($userTags, 'Теги успешно загружены.');
    }

    public function createUserTag(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tag_name' => 'required',
            'tag_ico' => 'required',
            'color' => 'nullable',
            'user_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }
        $input = $request->all();
        $tag = Tag::create($input);
        return $this->sendResponse($tag, 'Тег успешно создан.');
    }

    public function editTag(Request $request, $id)
    {
        $tag = Tag::find($id);
        $validator = Validator::make($request->all(), [
            'tag_name' => 'required',
            'tag_ico' => 'required',
            'color' => 'nullable',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $errorMessages);       
        }
        $tag->fill($request->all());
        $tag->save();
        return $this->sendResponse($tag, 'Данные тега успешно изменены.');
    }

    public function deleteTag($id)
    {
        $tag = Tag::find($id);

        if (is_null($tag)) {
            return $this->sendError('Тег не найден.');
        }

        $tag->delete();
        return $this->sendResponse($tag, 'Тег успешно удален.');
    }
}
