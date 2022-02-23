<?php

namespace Services;

use App\Helpers\GlobalsHelper;
use Repository\LoanRepository;
use Repository\RepaymentRepository;
use Illuminate\Http\Request;
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

    public function process(Request $a)
    {

        DB::beginTransaction();

        try {

            $insertData = [
                'loan_amount' => $a->loan_amount,
                'loan_tenure' => $a->loan_tenure,
                'created_at' => now()
            ];

            //put the loan in the table and generate the repayment scedule in weekly basis.

            //create one user table too.


            $returnData = $this->tokenRepo->insert($insertData);

            $currentDate =  strtotime(date('Y-m-d'));

            $startDate = date('Y-m-d', strtotime("+1 days", $currentDate));

            $startEmiDate = date('Y-m-d', strtotime("+7 days", $startDate));

            $emiAmount = ($a->loan_amount / ($a->loan_tenure * 4));

            for ($i = 0; $i < ($a->loan_tenure * 4); $i++) {

                $inc = 7;

                $emiScheduleData[] = [
                    'emi_amount' => $emiAmount,
                    'emi_date' => date('Y-m-d', strtotime("+" + $inc + " days", $startDate))

                ];

                $inc = $inc + 7;
            }

            $this->loanRepayment->insert($emiScheduleData);


            DB::commit();

            GlobalsHelper\funcReturnsData(true, "successful", ['id' => $returnData], 200);
        } catch (\Exception $ex) {

            DB::rollback();
            Log::info($ex);
            GlobalsHelper\funcReturnsData(false, "error", ['error' => $ex->getMessage()], 400);
        }
    }
}
