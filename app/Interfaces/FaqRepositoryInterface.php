<?php

namespace App\Interfaces;

interface FaqRepositoryInterface
{
    public function createFaq(array $faq);
    public function readFaq(int $faq);
    public function updateFaq(int $faq, $new_faq);
    public function deleteFaq(int $faq);

    public function readAllFaq();
}
