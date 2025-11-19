<?php

namespace App\Repositories;

use App\Interfaces\FaqCategoryRepositoryInterface;
use App\Models\FaqCategory;

class FaqCategoryRepository implements FaqCategoryRepositoryInterface
{
    public function createFaqCategory(array $faqcategory)
    {
        return FaqCategory::create($faqcategory);
    }

    public function readFaqCategory(int $faqcategory)
    {
        return FaqCategory::findOrFail($faqcategory);
    }

    public function updateFaqCategory(int $faqcategory, $new_faqcategory)
    {
        return FaqCategory::where('id', $faqcategory)->update($new_faqcategory);
    }

    public function deleteFaqCategory(int $faqcategory)
    {
        return FaqCategory::destroy($faqcategory);
    }

    public function readAllFaqCategory()
    {
        return FaqCategory::all();
    }
}
