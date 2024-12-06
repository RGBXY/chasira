<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getUserIdForQuery')) {
    function getUserIdForQuery()
    {
        // Mengembalikan parent_id jika ada, atau id user yang login
        return Auth::user()->parent_id ?: Auth::id();
    }
}