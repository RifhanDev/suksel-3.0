<?php

namespace App\Http\Controllers\Concerns;

use App\Support\ReportAccess;

trait AuthorizesReportAccess
{
    /**
     * @return \Illuminate\Http\Response|null
     */
    protected function denyUnlessReport(string ...$permissions)
    {
        if (! ReportAccess::allows(auth()->user(), ...$permissions)) {
            return $this->_access_denied();
        }

        return null;
    }
}
