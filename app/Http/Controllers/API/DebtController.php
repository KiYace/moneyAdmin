<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\Category;
use App\Models\Source;
use App\Models\Debt;
use App\Models\DebtReminder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DebtController extends BaseController
{
    // DEBT_TYPE = 0 - заняли у клиента
    // DEBT_TYPE = 1 - клиент занял у кого-то


    public function getUserDebt($id)
    {
        $debts = Debt::where('user_id', $id)->get();

        if(is_null($debts)) {
            return $this->sendError('Долги не найдены.');
        }

        return $this->sendResponse($debts, 'Долги успешно загружены.');
    }

    public function createUserDebt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'debt_name' => 'required',
            'debt_desc' => 'nullable',
            'debt_type' => 'required',
            'user_id' => 'required',
            'bill_id' => 'required',
            'debt_sum' => 'required',
            'debt_currency' => 'required',
            'debt_finish' => 'required',
            'debt_reminder' => 'required',
            'debt_important' => 'required',
            'debt_active' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }

        $input = $request->all();
        if(!empty($request->debt_sum)) {
            if($request->debt_type  == 0) {
                $bill = Bill::find($request->bill_id);
                $bill->balance = $bill->balance - $request->debt_sum;
                $category = Category::where('category_name', 'Долг '.$request->debt_name)->first();
                if(empty($category)) {
                    $categoryInput = array(
                        'category_name' => 'Долг '.$request->debt_name,
                        'category_ico' => 'check',
                        'color' => '#'.dechex(mt_rant(0, 16777215)),
                        'user_id' => $request->user_id,
                        'display' => false
                    );

                    $category = Category::create($categoryInput);
                }
                $expenseInput = array(
                    'user_id' => $request->user_id,
                    'category_id' => $category->id,
                    'bill_id' => $request->bill_id,
                    'shop' => '123123',
                    'sum' => $request->debt_sum,
                    'important' => $request->debt_important
                );
                $expense = Expenses::create($expenseInput);
                $bill->save();
            } elseif($request->debt_type  == 1) {
                $bill = Bill::find($request->bill_id);
                $bill->balance = $bill->balance + $request->debt_sum;
                $source = Source::where('source_name', 'Долг '.$debt->debt_sum)->first();
                if(empty($source)) {
                    $sourceInput = array(
                        'source_name' => 'Долг '.$debt->debt_name,
                        'source_ico' => 'check',
                        'color' => '#'.dechex(mt_rant(0, 16777215)),
                        'user_id' => $request->user_id,
                        'display' => false
                    );
                
                    $source = Source::create($sourceInput);
                }
                $incomeInput = array(
                    'user_id' => $request->user_id,
                    'source' => $source->id,
                    'sum' => $request->debt_sum,
                    'bill_id' => $request->bill_id,
                    'shop' => '123123',
                    'important' => $request->debt_important,
                );
                $income = Income::create($expenseInput);
                $bill->save();
            }
                
        }

        $debt = Debt::create($input);

         // Создание повтора для расхода
         switch ($request->debt_reminder) {
            case '0':
                return $this->sendResponse($debt, 'Долг успешно создан.');
                break;
            case '1':
                $repeatDate = $debt->created_at->addDays(1);
                break;
            case '2':   
                $repeatDate = $debt->created_at->addDays(7);
                break;
            case '3':   
                $repeatDate = $debt->created_at->addDays(30);
                break;
            
        }
        $debtReminderInput = [
            'debt_id' => $debt->id,
            'debt_type' => $debt->type,
            'debt_reminder' => $debt->debt_reminder,
            'debt_reminder_date' => $repeatDate
        ];

        $debtReminder = DebtReminder::create($debtReminderInput);

        return $this->sendResponse($debt, 'Долг успешно создан.');
    }

    public function editUserDebt(Request $request)
    {
        $debt = Debt::find($id);
        $validator = Validator::make($request->all(), [
            'debt_name' => 'required',
            'debt_desc' => 'nullable',
            'debt_type' => 'required',
            'user_id' => 'required',
            'bill_id' => 'required',
            'debt_sum' => 'required',
            'debt_currency' => 'required',
            'debt_finish' => 'required',
            'debt_reminder' => 'required',
            'debt_important' => 'required',
            'debt_active' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }

        /* Обработка изменения счета */
        if($debt->bill_id != $request->bill_id) {
            /* Изменился ли тип долга */
            if($request->debt_type == $debt->debt_type) {
                if($debt->debt_type == 0) {
                    $bill_increase = Bill::find($debt->bill_id);
                    $bill_increase->balance = $bill_increase->balance + $debt->debt_sum;
                    $bill_increase->save();
                    $bill_decrease = Bill::find($request->bill_id);
                    $bill_decrease->balance = $bill_decrease->balance - $request->debt_sum;
                    $bill_decrease->save();
                } else {
                    $bill_decrease = Bill::find($debt->bill_id);
                    $bill_decrease->balance = $bill_increase->balance - $debt->debt_sum;
                    $bill_decrease->save();
                    $bill_increase = Bill::find($request->bill_id);
                    $bill_increase->balance = $bill_decrease->balance + $request->debt_sum;
                    $bill_increase->save();
                }
            } elseif($request->debt_type != $debt->debt_type) {
                if($debt->debt_type == 0) {
                    $bill_increase = Bill::find($debt->bill_id);
                    $bill_increase->balance = $bill_increase->balance + $debt->debt_sum;
                    $bill_increase->save();
                    $bill_decrease = Bill::find($request->bill_id);
                    $bill_decrease->balance = $bill_decrease->balance - $request->debt_sum;
                    $bill_decrease->save();
                } else {
                    $bill_decrease = Bill::find($debt->bill_id);
                    $bill_decrease->balance = $bill_increase->balance - $debt->debt_sum;
                    $bill_decrease->save();
                    $bill_increase = Bill::find($request->bill_id);
                    $bill_increase->balance = $bill_decrease->balance + $request->debt_sum;
                    $bill_increase->save();
                }
            }
        }

        /* Изменился ли тип долга */
        if($request->debt_type != $debt->debt_type) {
            if($debt->debt_type == 0) {
                $bill_increase = Bill::find($debt->bill_id);
                $bill_increase->balance = $bill_increase->balance + $debt->debt_sum;
                $bill_increase->save();
                $bill_decrease = Bill::find($request->bill_id);
                $bill_decrease->balance = $bill_decrease->balance - $request->debt_sum;
                $bill_decrease->save();
            } else {
                $bill_decrease = Bill::find($debt->bill_id);
                $bill_decrease->balance = $bill_increase->balance - $debt->debt_sum;
                $bill_decrease->save();
                $bill_increase = Bill::find($request->bill_id);
                $bill_increase->balance = $bill_decrease->balance + $request->debt_sum;
                $bill_increase->save();
            }
        }

        /* Обработка изменения баланса */
        if($debt->debt_sum != $request->debt_sum) {
            if($debt->debt_type == 0) {
                $bill = Bill::find($debt->bill_id);
                $bill->balance = $bill->balance + $debt->debt_sum;
                $bill->balance = $bill->balance - $request->debt_sum;
                $bill->save();
            } elseif($debt->debt_type == 1) {
                $bill = Bill::find($debt->bill_id);
                $bill->balance = $bill->balance - $debt->debt_sum;
                $bill->balance = $bill->balance + $request->debt_sum;
                $bill->save();
            }
        }

        /* Обработка смены повтора */
        if($debt->debt_reminder != $request->debt_reminder) {
            if($debt->debt_reminder != 0) {
                $debtReminder = DebtReminder::where('debt_id', $debt->id);
                switch ($request->debt_reminder) {
                    case '0':
                        $debtReminder->delete();
                        break;
                    case '1':
                        $debtReminder->debt_reminder_date = $debt->created_at->addDays(1);
                        break;
                    case '2':
                        $debtReminder->debt_reminder_date = $debt->created_at->addDays(7);
                        break;
                    case '3':
                        $debtReminder->debt_reminder_date = $debt->created_at->addDays(30);
                        break;
                }

                $debtReminder->save();
            } else {
                // Создание повтора для расхода
                switch ($request->repeat) {
                    case '1':
                        $repeatDate = $debt->created_at->addDays(1);
                        break;
                    case '2':   
                        $repeatDate = $debt->created_at->addDays(7);
                        break;
                    case '3':   
                        $repeatDate = $debt->created_at->addDays(30);
                        break;
                    
                }

                $debtReminderInput = [
                    'debt_id' => $debt->id,
                    'debt_type' => $debt->debt_type,
                    'debt_reminder' => $debt->debt_reminder,
                    'debt_reminder_date' => $repeatDate
                ];
            
                $debtReminder = DebtReminder::create($debtReminderInput);
            }
        }

        $debt->fill($request->all());
        $debt->save();
        return $this->sendResponse($debt, 'Долг успешно изменен.');
    }

    public function endUserDebt($id)
    {
        $debt = Debt::find($id);
        $debt->debt_active = false;

        $bill = Bill::find($debt->bill_id);
        if($debt->debt_type == 0) {
            $bill->balance = $bill->balance + $debt->debt_sum;
            $source = Source::where('source_name', 'Завершение долга '.$debt->debt_name)->first();
            if(empty($source)) {
                $sourceInput = array(
                    'source_name' => 'Завершение долга '.$debt->debt_name,
                    'source_ico' => 'check',
                    'color' => '#'.dechex(mt_rant(0, 16777215)),
                    'user_id' => $debt->user_id,
                    'display' => false
                );
            
                $source = Source::create($sourceInput);
            }
            $incomeInput = array(
                'user_id' => $debt->user_id,
                'source' => $source->id,
                'sum' => $debt->debt_sum,
                'bill_id' => $debt->bill_id,
                'debt_id' => $debt->id,
                'shop' => '123123',
                'important' => $debt->debt_important,
            );
            $income = Income::create($incomeInput);
            $bill->save();
        } elseif($debt->debt_type == 1) {
            $bill->balance = $bill->balance - $debt->debt_sum;
            $category = Category::where('category_name', 'Завершение долга '.$debt->debt_name)->first();
            if(empty($category)) {
                $categoryInput = array(
                    'category_name' => 'Завершение долга '.$debt->debt_name,
                    'category_ico' => 'check',
                    'color' => '#'.dechex(mt_rant(0, 16777215)),
                    'user_id' => $debt->user_id,
                    'display' => false
                );

                $category = Category::create($categoryInput);
            }
            $expenseInput = array(
                'user_id' => $debt->user_id,
                'category_id' => $category->id,
                'bill_id' => $debt->bill_id,
                'debt_id' => $debt->id,
                'shop' => '123123',
                'sum' => $debt->debt_sum,
                'important' => $debt->debt_important,
            );
            $expense = Expenses::create($expenseInput);
            $bill->save();
        }

        if($debt->debt_reminder != 0) {
            $debtReminder = DebtReminder::where('debt_id', $debt->id);
            $debtReminder->delete();
        }
        

        $debt->save();
        return $this->sendResponse($debt, 'Долг успешно завершен.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteDebt($id)
    {
        $debt = Debt::find($id);
        $bill = Bill::find($debt->bill_id);
        if($debt->debt_type == 0) {
            $bill->balance = $bill->balance + $debt->debt_sum;
            $expenses = Expenses::where('debt_id', $debt->debt_id);
            $expenses->delete();
        } elseif($debt->debt_type == 1) {
            $bill->balance = $bill->balance - $debt->debt_sum;
            $incomes = Incomes::where('debt_id', $debt->debt_id);
            $incomes->delete();
        }

        if($debt->debt_reminder != 0) {
            $debtReminder = DebtReminder::where('debt_id', $debt->id);
            $debtReminder->delete();
        }
        
        $bill->save();
        $debt->delete();

        return $this->sendResponse($debt, 'Долг успешна удален.');
    }
}
