<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\incomes;
use App\Models\Income;
use App\Models\Source;
use App\Models\IncomesRepeat;
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
        $bill = Bill::find($income->bill_id);
        $bill->balance = $bill->balance + $income->sum;
        $income = Income::create($input);
        $bill->save();

        // Создание повтора для расхода
        switch ($request->repeat) {
            case '0':
                return $this->sendResponse($income, 'Доход успешно создан.');
                break;
            case '1':
                $repeatDate = $income->created_at->addDays(1);
                break;
            case '2':   
                $repeatDate = $income->created_at->addDays(7);
                break;
            case '3':   
                $repeatDate = $income->created_at->addDays(30);
                break;
            
        }
        $incomeRepeatInput = [
            'income_id' => $income->id,
            'income_repeat' => $income->repeat,
            'income_repeat_date' => $repeatDate
        ];

        $incomeRepeat = IncomesRepeat::create($incomeRepeatInput);


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

        /* Обработка смены повтора */
        if($income->repeat != $request->repeat) {
            if($income->repeat != 0) {
                $incomeRepeat = IncomesRepeat::where('expnense_id', $income->id);
                switch ($request->repeat) {
                    case '0':
                        $incomeRepeat->delete();
                        break;
                    case '1':
                        $incomeRepeat->income_repeat_date = $income->created_at->addDays(1);
                        break;
                    case '2':
                        $incomeRepeat->income_repeat_date = $income->created_at->addDays(7);
                        break;
                    case '3':
                        $incomeRepeat->income_repeat_date = $income->created_at->addDays(30);
                        break;
                }

                $incomeRepeat->save();
            } else {
                // Создание повтора для расхода
                switch ($request->repeat) {
                    case '1':
                        $repeatDate = $income->created_at->addDays(1);
                        break;
                    case '2':   
                        $repeatDate = $income->created_at->addDays(7);
                        break;
                    case '3':   
                        $repeatDate = $income->created_at->addDays(30);
                        break;
                    
                }

                $incomeRepeatInput = [
                    'income_id' => $income->id,
                    'income_repeat' => $income->repeat,
                    'income_repeat_date' => $repeatDate
                ];
            
                $incomeRepeat = IncomesRepeat::create($incomeRepeatInput);
            }
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

        if($income->repeat != 0) {
            $incomeRepeat = IncomesRepeat::where('expnense_id', $income->id);
            $incomeRepeat->delete();
        }

        $income->delete();
        return $this->sendResponse($income, 'Доход успешно удален.');
    }
}
