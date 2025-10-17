<?php

namespace App\Repositories;

use App\Interfaces\CustomerQuestionRepositoryInterface;
use App\Models\CustomerQuestion;

class CustomerQuestionRepository implements CustomerQuestionRepositoryInterface
{
    public function createCustomerQuestion(array $customerquestion)
    {
        return CustomerQuestion::create($customerquestion);
    }

    public function readCustomerQuestion(int $customerquestion)
    {
        return CustomerQuestion::findOrFail($customerquestion);
    }

    public function updateCustomerQuestion(int $customerquestion, $new_customerquestion)
    {
        return CustomerQuestion::where('id', $customerquestion)->update($new_customerquestion);
    }

    public function deleteCustomerQuestion(int $customerquestion)
    {
        return CustomerQuestion::destroy($customerquestion);
    }

    public function readAllCustomerQuestion()
    {
        return CustomerQuestion::all();
    }
}
