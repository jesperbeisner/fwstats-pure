<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action\Result;

use Jesperbeisner\Fwstats\Action\Exception\ActionResultException;
use Jesperbeisner\Fwstats\Model\User;

final class CreateUserActionResult extends AbstractActionResult
{
    public function getUser(): User
    {
        if (isset($this->data['user']) && $this->data['user'] instanceof User) {
            return $this->data['user'];
        }

        throw new ActionResultException('No user in data array available.');
    }
}
