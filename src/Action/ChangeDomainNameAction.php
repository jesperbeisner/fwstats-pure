<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action;

use Jesperbeisner\Fwstats\Exception\ActionException;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ActionInterface;
use Jesperbeisner\Fwstats\Interface\ActionResultInterface;
use Jesperbeisner\Fwstats\Repository\ConfigRepository;
use Jesperbeisner\Fwstats\Result\ActionResult;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Action\ChangeDomainNameActionTest
 */
final class ChangeDomainNameAction implements ActionInterface
{
    private string $domainName;

    public function __construct(
        private readonly ConfigRepository $configRepository,
    ) {
    }

    public function configure(array $data): ActionInterface
    {
        if (!isset($data['domainName'])) {
            throw new RuntimeException('No domain name set in "ChangeDomainNameAction::configure".');
        }

        if (!is_string($data['domainName'])) {
            throw new RuntimeException('The domain name set in the "ChangeDomainNameAction::configure" method is not a string.');
        }

        if (!str_starts_with($data['domainName'], 'https://')) {
            throw new ActionException('text.domain-name-wrong-start');
        }

        $this->domainName = $data['domainName'];

        return $this;
    }

    public function run(): ActionResultInterface
    {
        if (!isset($this->domainName)) {
            throw new RuntimeException('You need to call "configure" before you can call "run".');
        }

        $this->configRepository->changeDomainName($this->domainName);

        return new ActionResult(ActionResultInterface::SUCCESS, 'text.domain-name-changed-successfully');
    }
}
