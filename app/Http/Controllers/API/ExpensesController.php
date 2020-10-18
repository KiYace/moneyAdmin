<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\ExpensesRepeat;
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

    public function getCategoryExpenses($id)
    {
        $expenses = Expenses::where('category_id', $id)->get();
        if (is_null($expenses)) {
            return $this->sendError('Расходы не найдены.');
        }
        return $this->sendResponse($expenses->toArray(), 'Расходы успешно загружены.');
    }

    public function getTagExpenses($id)
    {
        $expenses = Expenses::whereJsonContains('tags_id', $id)->get();
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
        $bill = Bill::find($request->bill_id);
        $bill->balance = $bill->balance - $request->sum;
        $expense = Expenses::create($input);
        $bill->save();

        // Создание повтора для расхода
        switch ($request->repeat) {
            case '0':
                return $this->sendResponse($expense, 'Расход успешно создан.');
                break;
            case '1':
                $repeatDate = $expense->created_at->addDays(1);
                break;
            case '2':   
                $repeatDate = $expense->created_at->addDays(7);
                break;
            case '3':   
                $repeatDate = $expense->created_at->addDays(30);
                break;
            
        }
        $expenseRepeatInput = [
            'expense_id' => $expense->id,
            'expense_repeat' => $expense->repeat,
            'expense_repeat_date' => $repeatDate
        ];

        $expenseRepeat = ExpensesRepeat::create($expenseRepeatInput);
        
        
        return $this->sendResponse($expense, 'Расход успешно создан.');
    }

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
            $bill_decrease->balance = $bill_decrease->balance - $request->sum;
            $bill_decrease->save();
        }

        /* Обработка суммы */
        if($expense->sum != $request->sum) {
            $bill = Bill::find($expense->bill_id);
            $bill->balance = $bill->balance + $expense->sum;
            $bill->balance = $bill->balance - $request->sum;
            $bill->save();
        }

        /* Обработка смены повтора */
        if($expense->repeat != $request->repeat) {
            if($expense->repeat != 0) {
                $expenseRepeat = ExpensesRepeat::where('expnense_id', $expense->id);
                switch ($request->repeat) {
                    case '0':
                        $expenseRepeat->delete();
                        break;
                    case '1':
                        $expenseRepeat->expense_repeat_date = $expense->created_at->addDays(1);
                        break;
                    case '2':
                        $expenseRepeat->expense_repeat_date = $expense->created_at->addDays(7);
                        break;
                    case '3':
                        $expenseRepeat->expense_repeat_date = $expense->created_at->addDays(30);
                        break;
                }

                $expenseRepeat->save();
            } else {
                // Создание повтора для расхода
                switch ($request->repeat) {
                    case '1':
                        $repeatDate = $expense->created_at->addDays(1);
                        break;
                    case '2':   
                        $repeatDate = $expense->created_at->addDays(7);
                        break;
                    case '3':   
                        $repeatDate = $expense->created_at->addDays(30);
                        break;
                    
                }

                $expenseRepeatInput = [
                    'expense_id' => $expense->id,
                    'expense_repeat' => $expense->repeat,
                    'expense_repeat_date' => $repeatDate
                ];
            
                $expenseRepeat = ExpensesRepeat::create($expenseRepeatInput);
            }
        }
        
        $expense->fill($request->all());
        $expense->save();
        return $this->sendResponse($expense, 'Данные расхода успешно изменены.');
    }

    public function deleteExpense($id)
    {
        $expense = Expenses::find($id);

        if (is_null($expense)) {
            return $this->sendError('Расход не найден.');
        }

        $bill = Bill::find($expense->bill_id);
        $bill->balance = $bill->balance + $expense->sum;
        $bill->save();
        
        if($expense->repeat != 0) {
            $expenseRepeat = ExpensesRepeat::where('expnense_id', $expense->id);
            $expenseRepeat->delete();
        }

        $expense->delete();
        return $this->sendResponse($expense, 'Расход успешно удален.');
    }
}
