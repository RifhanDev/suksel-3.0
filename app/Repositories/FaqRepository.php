<?php

namespace App\Repositories;

use App\Interfaces\FaqRepositoryInterface;
use App\Models\Faq;

class FaqRepository implements FaqRepositoryInterface
{
    public function createFaq(array $faq)
    {
        return Faq::create($faq);
    }

    public function readFaq(int $faq)
    {
        return Faq::findOrFail($faq);
    }

    public function updateFaq(int $faq, $new_faq)
    {
        return Faq::where('id', $faq)->update($new_faq);
    }

    public function deleteFaq(int $faq)
    {
        return Faq::destroy($faq);
    }

    public function readAllFaq()
    {
        return Faq::all();
    }

    public function getFaqByCategoryId($faq_Category_id)
    {
        return Faq::where('faq_category_id', $faq_Category_id)->get();
    }
}
