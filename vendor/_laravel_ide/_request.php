<?php

namespace Illuminate\Http;

interface Request
{
    /**
     * @return \App\Models\SysLoginData|null
     */
    public function user($guard = null);
}