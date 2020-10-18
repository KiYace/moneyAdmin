<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Limit;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LimitController extends BaseController
{
    public function getUserLimits($id)
    {
        $limits = Limit::where('user_id', $id)->get();

        if(is_null($limits)) {
            return $this->sendError('Лимиты не найдены.');
        }

        return $this->sendResponse($limits, 'Лимиты успешно загружены.');
    }

    public function createUserLimit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'user_id' => 'required',
            'limit' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }

        $input = $request->all();
        $limit = Limit::create($input);
        return $this->sendResponse($limit, 'Лимит успешно создана.');
    }

    public function editLimit(Request $request, $id)
    {
        $limit = Limit::find($id);
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'user_id' => 'required',
            'limit' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $errorMessages);       
        }
        $limit->fill($request->all());
        $limit->save();
        return $this->sendResponse($limit, 'Данные лимимта успешно изменены.');
    }

    public function deleteLimit($id)
    {
        $limit = Limit::find($id);

        if (is_null($limit)) {
            return $this->sendError('Лимит не найден.');
        }

        $limit->delete();
        return $this->sendResponse($limit, 'Лимит успешно удален.');
    }
}
