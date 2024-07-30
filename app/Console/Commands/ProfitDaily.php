<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Account;

class ProfitDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profit:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Add Profit Daily in Users Account Balance & in Profit Balance By Referring to thier Plan Related with Transaction.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $transactions = Transaction::where('type','INVEST')->where('status','APPROVED')->with('User')->with('Plan')->get();
     
      foreach($transactions as $transaction){
        $mplan = Transaction::where('type','MEMBERSHIP FEE')->where('user_name',$transaction->user_name)->where('status','APPROVED')->first();
      if($mplan && $mplan->expiration_date > now()){
       if($transaction->expiration_date > now()){
        $acc = Account::where('user_name',$transaction->user_name)->first();
        // Add daily profit in account balance
        $old_acc_bal = $acc->balance;
        $new_acc_bal = $transaction->plan->profit_amount + $old_acc_bal;
        // Add Account balance and total profit to user table
        Account::where('user_name',$transaction->user_name)->update(['balance'=>$new_acc_bal]);  
        // Create Profit Transacton
        $pr_transaction = new Transaction();
        $pr_transaction->type = 'PROFIT';
        $pr_transaction->status = 'APPROVED';
        $pr_transaction->amount = $transaction->plan->profit_amount;
        $pr_transaction->user_name = $transaction->user_name;
        $pr_transaction->save();
       }else{
        Transaction::where('transaction_id',$transaction->transaction_id)->update(['status'=>'EXPIRED']);
       }
      }else{
        Transaction::where('transaction_id',$transaction->transaction_id)->update(['status'=>'EXPIRED']);
        Transaction::where('transaction_id',$mplan->transaction_id)->update(['status'=>'EXPIRED']);
      }
    }
        
    }        
}
