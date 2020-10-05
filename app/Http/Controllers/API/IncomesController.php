<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\Source;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class IncomesController extends BaseController
{
    public function getUserIncomes($id)
    {
        $incomes = Income::where('user_id', $id)->get();
        if (is_null($incomes)) {
            return $this->sendError('Доходы не найдены.');
        }
        return $this->sendResponse($incomes->toArray(), 'Доходы успешно загружены.');
    }

    public function getBillIncomes($id)
    {
        $incomes = Income::where('bill_id', $id)->get();
        if (is_null($incomes)) {
            return $this->sendError('Доходы не найдены.');
        }
        return $this->sendResponse($incomes->toArray(), 'Доходы успешно загружены.');
    }

    public function getSourceIncomes($id)
    {
        $incomes = Income::where('source', $id)->get();
        if (is_null($incomes)) {
            return $this->sendError('Доходы не найдены.');
        }
        return $this->sendResponse($incomes->toArray(), 'Доходы успешно загружены.');
    }

    public function getTagIncomes($id)
    {
        $incomes = Income::whereJsonContains('tags_id', $id)->get();
        if (is_null($incomes)) {
            return $this->sendError('Доходы не найдены.');
        }
        return $this->sendResponse($incomes->toArray(), 'Доходы успешно загружены.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createIncome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'source' => 'required',
            'sum' => 'required',
            'bill_id' => 'required',
            'shop' => 'required',
            'important' => 'required',
            'tags_id' => 'nullable',
            'notice' => 'nullable',
            'repeat' => 'nullable',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }
        $input = $request->all();
        $income = Income::create($input);
        $bill = Bill::find($income->bill_id);
        $bill->balance = $bill->balance + $income->sum;
        $bill->save();
        return $this->sendResponse($income, 'Доход успешно создан.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editIncome(Request $request, $id)
    {
        $income = Income::find($id);
        $validator = Validator::make($request->all(), [
            'source' => 'required',
            'sum' => 'required',
            'bill_id' => 'required',
            'shop' => 'nullable',
            'important' => 'nullable',
            'tags_id' => 'nullable',
            'notice' => 'nullable',
            'repeat' => 'nullable',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $errorMessages);       
        }

        /* Обработка счетов */
        if($income->bill_id != $request->bill_id) {
            $bill_decrease = Bill::find($income->bill_id);
            $bill_decrease->balance = $bill_increase->balance - $income->sum;
            $bill_decrease->save();
            $bill_increase = Bill::find($request->bill_id);
            $bill_increase->balance = $bill_decrease->balance + $request->sum;
            $bill_increase->save();
        }

        if($income->sum != $request->sum) {
            $bill = Bill::find($income->bill_id);
            $bill->balance = $bill->balance - $income->sum;
            $bill->balance = $bill->balance + $request->sum;
            $bill->save();
        }
        
        $income->fill($request->all());
        $income->save();
        return $this->sendResponse($income, 'Данные дохода успешно изменены.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteIncome($id)
    {
        $income = Income::find($id);

        if (is_null($income)) {
            return $this->sendError('Доход не найден.');
        }

        $bill = Bill::find($income->bill_id);
        $bill->balance = $bill->balance - $income->sum;
        $bill->save();

        $income->delete();
        return $this->sendResponse($income, 'Доход успешно удален.');
    }
}
