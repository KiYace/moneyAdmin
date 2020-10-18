<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Source;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SourceController extends BaseController
{
    public function getUserSources($id)
    {
        $soures = Source::where('user_id', $id)->get();

        if(is_null($soures)) {
            return $this->sendError('Источники не найдены.');
        }

        return $this->sendResponse($soures, 'Источники успешно загружены.');
    }

    public function createUserSource(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_name' => 'required',
            'source_ico' => 'required',
            'color' => 'required',
            'user_id' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }

        $input = $request->all();
        $source = Source::create($input);
        return $this->sendResponse($source, 'Источник успешно создана.');
    }

    public function editSource(Request $request, $id)
    {
        $source = Source::find($id);
        $validator = Validator::make($request->all(), [
            'source_name' => 'required',
            'source_ico' => 'required',
            'color' => 'required',
            'user_id' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $errorMessages);       
        }
        $source->fill($request->all());
        $source->save();
        return $this->sendResponse($source, 'Данные источника успешно изменены.');
    }

    public function deleteSource($id)
    {
        $source = Source::find($id);

        if (is_null($limit)) {
            return $this->sendError('Источник не найден.');
        }

        $source->delete();
        return $this->sendResponse($source, 'Источник успешно удален.');
    }
}