<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

class LogNonGetRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']) && $this->canLogInEnvironment();
    }

    public function canLogInEnvironment()
    {
        $environments = config('http-logger.environments');

        if ($environments == null || count($environments) == 0) {
            return false;
        }

        if (! in_array(app()->environment(), $environments)) {
            return false;
        }

        return true;
    }
}
