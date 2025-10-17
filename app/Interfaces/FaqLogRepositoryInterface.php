<?php

namespace App\Interfaces;

interface FaqLogRepositoryInterface
{
    public function createFaqLog(array $faqlog);
    public function readFaqLog(int $faqlog);
    public function updateFaqLog(int $faqlog, $new_faqlog);
    public function deleteFaqLog(int $faqlog);

    public function readAllFaqLog();
}
