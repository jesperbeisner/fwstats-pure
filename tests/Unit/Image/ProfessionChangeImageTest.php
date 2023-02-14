<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Image;

use Generator;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Image\ProfessionChangeImage;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Tests\Doubles\DatabaseDummy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jesperbeisner\Fwstats\Image\ProfessionChangeImage
 */
final class ProfessionChangeImageTest extends TestCase
{
    /**
     * @dataProvider provideWorldEnumData
     */
    public function test_it_creates_an_image_successfully(WorldEnum $worldEnum): void
    {
        $fileName = __DIR__ . sprintf('/../../../var/%s-profession-changes-test.png', $worldEnum->value);

        $database = new DatabaseDummy();
        $database->setSelectReturn([
            ['world' => $worldEnum->value, 'player_id' => 1, 'name' => 'Test-1', 'old_profession' => null, 'new_profession' => 'Alchemist', 'created' => date('Y-m-d H:i:s')],
            ['world' => $worldEnum->value, 'player_id' => 2, 'name' => 'Test-2', 'old_profession' => 'Alchemist', 'new_profession' => 'Sammler', 'created' => date('Y-m-d H:i:s')],
            ['world' => $worldEnum->value, 'player_id' => 3, 'name' => 'Test-3', 'old_profession' => 'Alchemist', 'new_profession' => null, 'created' => date('Y-m-d H:i:s')],
        ]);

        $playerProfessionHistoryRepository = new PlayerProfessionHistoryRepository($database);

        $professionChangeImage = new ProfessionChangeImage(
            __DIR__ . '/../../../var',
            __DIR__ . '/../../../data/Roboto-Light.ttf',
            'profession-changes-test.png',
            $playerProfessionHistoryRepository
        );

        self::assertFileDoesNotExist($fileName);

        $professionChangeImage->create($worldEnum);

        self::assertFileExists($fileName);
        self::assertTrue(unlink($fileName));
    }

    /**
     * @return Generator<array<WorldEnum>>
     */
    public function provideWorldEnumData(): Generator
    {
        yield 'ActionFreewar' => [WorldEnum::AFSRV];
        yield 'ChaosFreewar' => [WorldEnum::CHAOS];
    }
}
