<?php

namespace Services;

use App\Helpers\GlobalsHelper;
use Repository\LoanRepository;
use Repository\RepaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as Collect;
use DB;
use Config;

class LoanService
{

    protected $loanRepo;
    protected $loanRepayment;

    public function __construct(LoanRepository $loanRepo, RepaymentRepository $loanRepayment)
    {
        $this->loanRepo = $loanRepo;
        $this->loanRepayment = $loanRepayment;
    }

    public function process($a): Collect
    {

        DB::beginTransaction();

        try {

            $insertData = [
                'loan_amount' => $a->loan_amount,
                'loan_tenure' => $a->loan_tenure,
                'user_id' => $a->user_id,
                'created_at' => now()
            ];

            $currentDate =  strtotime(date('Y-m-d'));

            $startDate = date('Y-m-d', strtotime("+1 days", $currentDate));

            $startEmiDate = date('Y-m-d', strtotime("+7 days", $startDate));

            $emiAmount = ($a->loan_amount / ($a->loan_tenure * 4));

            $insertData['emi_amount'] = $emiAmount;
            
            $returnData = $this->tokenRepo->insert($insertData);

            for ($i = 0; $i < ($a->loan_tenure * 4); $i++) {

                $inc = 7;

                $emiScheduleData[] = [
                    'emi_amount' => $emiAmount,
                    'emi_date' => date('Y-m-d', strtotime("+" + $inc + " days", $startDate)),
                    'loan_id' => $returnData['id']
                ];

                $inc = $inc + 7;
            }

            $this->loanRepayment->insert($emiScheduleData);

            DB::commit();

            return  GlobalsHelper\funcReturnsData(true, "successful", ['id' => $returnData['id']], 200);
        } catch (\Exception $ex) {

            DB::rollback();
            Log::info($ex);
            return GlobalsHelper\funcReturnsData(false, "error", ['error' => $ex->getMessage()], 400);
        }
    }
}
