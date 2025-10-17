<?php

namespace App\Interfaces;

interface FaqCategoryRepositoryInterface
{
    public function createFaqCategory(array $faqcategory);
    public function readFaqCategory(int $faqcategory);
    public function updateFaqCategory(int $faqcategory, $new_faqcategory);
    public function deleteFaqCategory(int $faqcategory);

    public function readAllFaqCategory();
}
