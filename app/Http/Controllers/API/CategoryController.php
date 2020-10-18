<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Category;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    public function getUserCategories($id)
    {
        $categories = Category::where('user_id', $id)->get();
        $allCategories = Category::where('user_id', null)->get();

        if(!empty($categories)) {
            $categories = $categories->toArray();
        }
        if(!empty($allCategories)) {
            $allCategories = $allCategories->toArray();
        }

        $userCategories = array_merge($categories, $allCategories);
        if(is_null($userCategories)) {
            return $this->sendError('Категории не найдены.');
        }

        return $this->sendResponse($userCategories, 'Категории успешно загружены.');
    }

    public function createUserCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_ico' => 'required',
            'color' => 'nullable',
            'user_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }
        $input = $request->all();
        $category = Category::create($input);
        return $this->sendResponse($category, 'Категория успешно создана.');
    }

    public function editCategory(Request $request, $id)
    {
        $category = Category::find($id);
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_ico' => 'required',
            'color' => 'nullable',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $errorMessages);       
        }
        $category->fill($request->all());
        $category->save();
        return $this->sendResponse($category, 'Данные категории успешно изменены.');
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return $this->sendError('Категория не найдена.');
        }

        $category->delete();
        return $this->sendResponse($category, 'Категория успешно удалена.');
    }
}
