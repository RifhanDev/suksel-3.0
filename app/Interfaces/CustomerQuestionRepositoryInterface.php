<?php

namespace App\Interfaces;

interface CustomerQuestionRepositoryInterface
{
    public function createCustomerQuestion(array $customerquestion);
    public function readCustomerQuestion(int $customerquestion);
    public function updateCustomerQuestion(int $customerquestion, $new_customerquestion);
    public function deleteCustomerQuestion(int $customerquestion);

    public function readAllCustomerQuestion();
}
