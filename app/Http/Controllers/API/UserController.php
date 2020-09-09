<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Appuser;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sec_name' => 'required',
            'email' => 'required|email',
            'salary' => 'nullable',
            'income' => 'nullable',
            'expenses' => 'nullable',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }
        $input = $request->all();
        $input['token'] = Str::random(64);

        /* push токен заменить на токен из приложения */
        $input['push_token']= Str::random(255);
        /* END push token */

        $input['password'] = bcrypt($input['password']);
        $user = Appuser::create($input);
        return $this->sendResponse($user, 'Пользователь успешно зарегестрирован.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUserById($id)
    {
        $appuser = Appuser::find($id);
        if (is_null($appuser)) {
            return $this->sendError('Пользователь не найден.');
        }
        return $this->sendResponse($appuser->toArray(), 'Пользователь успешно найден.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editUser(Request $request, $id)
    {
        $appuser = Appuser::find($id);
        $appuser->fill($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:appusers',
            'sec_name' => 'required',
            'email' => 'required|email|unique:appusers',
            'salary' => 'nullable',
            'income' => 'nullable',
            'expenses' => 'nullable',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $errorMessages);       
        }
        $appuser->save();
        return $this->sendResponse($appuser, 'Данные пользовтаеля успешно изменены.');
    }
    public function userLogin(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required_if:email,""',
            'email' => 'required_if:name,""|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }
        $input = $request->all();
        if(!empty($input['name'])) {
            $appuser = Appuser::where('name', $input['name']) -> first();
        } else {
            $appuser = Appuser::where('name', $input['email']) -> first();
        }

        /* Проверка совпадения паролей */
        if(!\Hash::check($input['password'], $appuser->password)) {
            return $this->sendError('Неверный логин или пароль.');    
        }
        /* END Проверка совпадения паролей */

        $appuser->token = Str::random(64);
        $appuser->save();
        return $this->sendResponse($appuser, 'Авторизация пройдена успешна.');
    }
}
