<?php

namespace App\Http\Repository;

use App\Models\Loan;



class RepaymentRepository  extends BaseRepository
{

    public function __construct(Loan $model)
    {
        parent::__construct($model);
    }

    public function getSchedule(int $loan_id)
    {
        return  $this->model->where('loan_id', $loan_id)->get();
    }
}
