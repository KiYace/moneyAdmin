<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Bill;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\Category;
use App\Models\Source;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GoalController extends BaseController
{
    public function getUserGoals($id)
    {
        $goals = Goal::where('user_id', $id)->get();

        if(is_null($goals)) {
            return $this->sendError('Цели не найдены.');
        }

        return $this->sendResponse($goals, 'Цели успешно загружены.');
    }

    public function createUserGoal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal_name' => 'required',
            'goal_description' => 'nullable',
            'goal_sum' => 'required',
            'goal_currency' => 'required',
            'goal_reminder' => 'required',
            'user_id' => 'required',
            'bill_id' => 'required',
            'goal_finish' => 'required',
            'goal_important' => 'required',
            'goal_active' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }

        $input = $request->all();
        if(!empty($request->goal_balance)) {
            $bill = Bill::find($request->bill_id);
            $bill->balance = $bill->balance - $request->goal_balance;
            $category = Category::where('category_name', 'Цель '.$request->goal_name)->first();
            if(empty($category)) {
                $categoryInput = array(
                    'category_name' => 'Цель '.$request->goal_name,
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
                'sum' => $request->goal_balance,
                'important' => $request->goal_important
            );
            $expense = Expenses::create($expenseInput);
            $bill->save();
        }
        $goal = Goal::create($input);
        return $this->sendResponse($goal, 'Цель успешно создана.');
    }

    public function editUserGoal(Request $request)
    {
        $goal = Goal::find($id);
        $validator = Validator::make($request->all(), [
            'goal_name' => 'required',
            'goal_description' => 'nullable',
            'goal_sum' => 'required',
            'goal_currency' => 'required',
            'goal_reminder' => 'required',
            'user_id' => 'required',
            'bill_id' => 'required',
            'goal_finish' => 'required',
            'goal_important' => 'required',
            'goal_active' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Ошибка валидации.', $validator->errors());       
        }

        /* Обработка изменения счета */
        if($goal->bill_id != $request->bill_id) {
            $bill_increase = Bill::find($goal->bill_id);
            $bill_increase->balance = $bill_increase->balance + $goal->goal_balance;
            $bill_increase->save();
            $bill_decrease = Bill::find($request->bill_id);
            $bill_decrease->balance = $bill_decrease->balance - $request->goal_balance;
            $bill_decrease->save();
        }

        /* Обработка изменения баланса */
        if($goal->goal_balance != $request->goal_balance) {
            $bill = Bill::find($goal->bill_id);
            $bill->balance = $bill->balance + $goal->goal_balance;
            $bill->balance = $bill->balance - $request->sum;
            $bill->save();
        }

        $goal->fill($request->all());
        $goal->save();
        return $this->sendResponse($goal, 'Цель успешно изменена.');
    }

    public function endUserGoal($id)
    {
        $goal = Goal::find($id);
        $goal->goal_active = false;

        $bill = Bill::find($goal->bill_id);
        $bill->balance = $bill->balance + $goal->goal_balance;
        $source = Source::where('source_name', 'Завершение цели '.$goal->goal_name)->first();
        if(empty($source)) {
            $sourceInput = array(
                'source_name' => 'Завершение цели '.$goal->goal_name,
                'source_ico' => 'check',
                'color' => '#'.dechex(mt_rant(0, 16777215)),
                'user_id' => $goal->user_id,
                'display' => false
            );

            $source = Source::create($sourceInput);
        }
        $incomeInput = array(
            'user_id' => $goal->user_id,
            'source' => $source->id,
            'sum' => $goal->goal_balance,
            'bill_id' => $goal->bill_id,
            'shop' => '123123',
            'important' => $goal->goal_important,
        );

        $goal->save();
        return $this->sendResponse($goal, 'Цель успешно завершена.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteGoal($id)
    {
        $goal = Goal::find($id);
        $bill = Bill::find($goal->bill_id);
        $bill->balance = $bill->balance + $goal->goal_balance;
        $bill->save();
        $goal->delete();

        return $this->sendResponse($goal, 'Цель успешна удалена.');
    }
}
