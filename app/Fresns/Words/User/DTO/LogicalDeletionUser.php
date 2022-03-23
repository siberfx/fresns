<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Fresns\Words\User\DTO;

use Fresns\DTO\DTO;

class LogicalDeletionUser extends DTO
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'accountId' => 'integer',
        ];
    }
}
