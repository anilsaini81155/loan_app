<?php

namespace Repository;

use App\Models\Loan;



class LoanRepository  extends BaseRepository{

    public function __construct(Loan $model) {
        parent::__construct($model);
    }

}
