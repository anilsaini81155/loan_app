<?php

namespace App\Http\Repository;

use App\Models\Repayment;



class RepaymentRepository  extends BaseRepository
{

    public function __construct(Repayment $model)
    {
        parent::__construct($model);
    }

    public function getSchedule(int $loan_id)
    {
        return  $this->model->where('loan_id', $loan_id)->get();
    }
}
