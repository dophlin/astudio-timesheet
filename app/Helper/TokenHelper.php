<?php

namespace App\Helper;

use App\Models\User;

class TokenHelper
{
    public static function revokeAllTokens($user_id) {
        try {
            $user = User::find($user_id);

            if ($user) {
                $user->tokens()->delete();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            \Log::error('Unable to revoke tokens. ' . $e->getMessage());
            return false;
        }
    }
}
