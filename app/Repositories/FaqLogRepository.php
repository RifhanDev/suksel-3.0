<?php

namespace App\Repositories;

use App\Interfaces\FaqLogRepositoryInterface;
use App\Models\FaqLog;

class FaqLogRepository implements FaqLogRepositoryInterface
{
    public function createFaqLog(array $faqlog)
    {
        return FaqLog::create($faqlog);
    }

    public function readFaqLog(int $faqlog)
    {
        return FaqLog::findOrFail($faqlog);
    }

    public function updateFaqLog(int $faqlog, $new_faqlog)
    {
        return FaqLog::where('id', $faqlog)->update($new_faqlog);
    }

    public function deleteFaqLog(int $faqlog)
    {
        return FaqLog::destroy($faqlog);
    }

    public function readAllFaqLog()
    {
        return FaqLog::all();
    }
}
