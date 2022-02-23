<?php

namespace Repository;

use App\Models\Loan;


class UserRepository  extends BaseRepository
{

    public function __construct(Loan $model)
    {
        parent::__construct($model);
    }

    public function checkUserExists(int $mobile_no, string $email)
    {
        return  $this->model->where(['mobile_no' => $mobile_no, 'email' => $email])->get();
    }
}
