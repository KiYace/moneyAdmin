<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\ExpensesRepeat;
use App\Models\IncomesRepeat;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\Appuser;
use App\Models\Bill;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $expensesRepeats = ExpensesRepeat::where('expense_repeat_date', '<', Carbon::now())->get();
       
            foreach($expensesRepeats as $repeat)
            {
                $expense = Expenses::find($repeat->expense_id);
                
                $newExpenseInput = [
                    'user_id' => $expense->user_id,
                    'category_id' => $expense->category_id,
                    'sum' => $expense->sum,
                    'bill_id' => $expense->bill_id,
                    'shop' => $expense->shop,
                    'important' => $expense->important,
                    'tags_id' => $expense->tags_id,
                    'notice' => $expense->notice,
                    'repeat' => 0
                ];

                $newExpense = Expenses::create($newExpenseInput);

                $bill = Bill::find($expense->bill_id);
                $bill->balance = $bill->balance - $expense->sum;
                $bill->save();

                switch ($repeat->expense_repeat) {
                    case '1':
                        $repeatDate = new Carbon($repeat->expense_repeat_date);
                        $repeatDate = $repeatDate->addDays(1);
                        break;
                    case '2':   
                        $repeatDate = new Carbon($repeat->expense_repeat_date);
                        $repeatDate = $repeatDate->addDays(7);
                        break;
                    case '3':   
                        $repeatDate = new Carbon($repeat->expense_repeat_date);
                        $repeatDate = $repeatDate->addDays(30);
                        break;
                }
                $repeat->expense_repeat_date = $repeatDate;
                $repeat->save();
            }

            $incomesRepeats = IncomesRepeat::where('incomes_repeat_date', '<', Carbon::now())->get();

            foreach($incomesRepeats as $repeat)
            {
                $icome = Income::find($repeat->income_id);
                
                $newIncomeInput = [
                    'user_id' => $income->user_id,
                    'sum' => $income->sum,
                    'bill_id' => $income->bill_id,
                    'goal_id' => $income->goal_id,
                    'source' => $income->source,
                    'important' => $income->important,
                    'tags_id' => $income->tags_id,
                    'notice' => $income->notice,
                    'repeat' => 0
                ];

                $newIncome = Income::create($newIncomeInput);

                $bill = Bill::find($income->bill_id);
                $bill->balance = $bill->balance + $income->sum;
                $bill->save();

                switch ($repeat->income_repeat) {
                    case '1':
                        $repeatDate = new Carbon($repeat->expense_repeat_date);
                        $repeatDate = $repeatDate->addDays(1);
                        break;
                    case '2':   
                        $repeatDate = new Carbon($repeat->expense_repeat_date);
                        $repeatDate = $repeatDate->addDays(7);
                        break;
                    case '3':   
                        $repeatDate = new Carbon($repeat->expense_repeat_date);
                        $repeatDate = $repeatDate->addDays(30);
                        break;
                }
                $repeat->income_repeat_date = $repeatDate;
                $repeat->save();
            }

        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
