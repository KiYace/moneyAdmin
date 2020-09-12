<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Expenses;
use App\Models\Income;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ExpensesController extends BaseController
{
    public function getUserExpenses($id)
    {
        $expenses = Expenses::where('user_id', $id)->get();
        if (is_null($expenses)) {
            return $this->sendError('Расходы не найдены.');
        }
        return $this->sendResponse($expenses->toArray(), 'Расходы успешно загружены.');
    }

    public function getBillExpenses($id)
    {
        $expenses = Expenses::where('bill_id', $id)->get();
        if (is_null($expenses)) {
            return $this->sendError('Расходы не найдены.');
        }
        return $this->sendResponse($expenses->toArray(), 'Расходы успешно загружены.');
    }

    public function getUserTransactions($id)
    {
        $expenses = Expenses::where('user_id', $id)->get();
        $incomes = Income::where('user_id', $id)->get();
        $transactions = array_merge($expenses->toArray(), $incomes->toArray());
        return $this->sendResponse($transactions, 'Транзакции успешно загружены.');
    }

    public function getUserTransactionsByBill($id)
    {
        $expenses = Expenses::where('bill_id', $id)->get();
        $incomes = Income::where('bill_id', $id)->get();
        $transactions = array_merge($expenses->toArray(), $incomes->toArray());
        return $this->sendResponse($transactions, 'Транзакции успешно загружены.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createExpense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'category_id' => 'required',
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
        $expense = Expenses::create($input);
        $bill = Bill::find($expense->bill_id);
        $bill->balance = $bill->balance - $request->sum;
        $bill->save();
        return $this->sendResponse($expense, 'Расход успешно создан.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editExpense(Request $request, $id)
    {
        $expense = Expenses::find($id);
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
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
        if($expense->bill_id != $request->bill_id) {
            $bill_increase = Bill::find($expense->bill_id);
            $bill_increase->balance = $bill_increase->balance + $expense->sum;
            $bill_increase->save();
            $bill_decrease = Bill::find($request->bill_id);
            $bill_decrease->balance = $bill_decrease->balance - $expense->sum;
            $bill_decrease->save();
        }
        
        $expense->fill($request->all());
        $expense->save();
        return $this->sendResponse($expense, 'Данные расхода успешно изменены.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteExpense($id)
    {
        $expense = Expenses::find($id);

        if (is_null($expense)) {
            return $this->sendError('Расход не найден.');
        }

        $bill = Bill::find($expense->bill_id);
        $bill->balance = $bill->balance + $expense->sum;
        $bill->save();

        $expense->delete();
        return $this->sendResponse($expense, 'Расход успешно удален.');
    }
}
