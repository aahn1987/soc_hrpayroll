<?php

namespace Illuminate\Support\Facades;

interface Auth
{
    /**
     * @return \App\Models\SysLoginData|false
     */
    public static function loginUsingId(mixed $id, bool $remember = false);

    /**
     * @return \App\Models\SysLoginData|false
     */
    public static function onceUsingId(mixed $id);

    /**
     * @return \App\Models\SysLoginData|null
     */
    public static function getUser();

    /**
     * @return \App\Models\SysLoginData
     */
    public static function authenticate();

    /**
     * @return \App\Models\SysLoginData|null
     */
    public static function user();

    /**
     * @return \App\Models\SysLoginData|null
     */
    public static function logoutOtherDevices(string $password);

    /**
     * @return \App\Models\SysLoginData
     */
    public static function getLastAttempted();
}