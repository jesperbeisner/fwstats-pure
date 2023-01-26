<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\Cronjob;

final class CronjobRepository extends AbstractRepository
{
    public function insert(Cronjob $cronjob): Cronjob
    {
        $sql = "INSERT INTO cronjobs (created) VALUES (:created)";

        $id = $this->database->insert($sql, ['created' => $cronjob->created->format('Y-m-d H:i:s')]);

        return Cronjob::withId($id, $cronjob);
    }

    public function findLastCronjob(): ?Cronjob
    {
        $sql = "SELECT id, created FROM cronjobs ORDER BY id DESC LIMIT 1";

        /** @var null|array{id: int, created: string} $result */
        $result = $this->database->selectOne($sql);

        if ($result === null) {
            return null;
        }

        return $this->hydrateCronjob($result);
    }

    /**
     * @param array{id: int, created: string} $row
     */
    private function hydrateCronjob(array $row): Cronjob
    {
        return new Cronjob($row['id'], new DateTimeImmutable($row['created']));
    }
}
