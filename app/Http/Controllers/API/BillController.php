<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Appuser;
use App\Models\Bill;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BillController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserBills($id)
    {
        $bills = Bill::where('user_id', $id)->get();
        if (is_null($bills)) {
            return $this->sendError('Счет не найден.');
        }
        return $this->sendResponse($bills->toArray(), 'Счет успешно найден.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createUserBill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bill_name' => 'required',
            'balance' => 'required',
            'currency' => 'nullable',
            'limit' => 'nullable',
            'user_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }
        $input = $request->all();
        $bill = Bill::create($input);
        return $this->sendResponse($bill, 'Счет успешно создан.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editBill(Request $request, $id)
    {
        $bill = Bill::find($id);
        $validator = Validator::make($request->all(), [
            'bill_name' => 'required',
            'balance' => 'required',
            'currency' => 'nullable',
            'limit' => 'nullable',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $errorMessages);       
        }
        $bill->fill($request->all());
        $bill->save();
        return $this->sendResponse($bill, 'Данные счета успешно изменены.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBill($id)
    {
        $bill = Bill::find($id);
        if (is_null($bill)) {
            return $this->sendError('Счет не найден.');
        }
        $bill->delete();
        return $this->sendResponse($bill, 'Счет успешно удален.');
    }
}
