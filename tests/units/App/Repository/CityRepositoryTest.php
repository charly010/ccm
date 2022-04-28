<?php

namespace units\App\Repository;

use App\Entity\City;
use App\Repository\CityRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Traversable;

class CityRepositoryTest extends TestCase
{
    private const YVELINES_ID = 108;
    private const YVELINE_MAIN_CITY = 'Versailles';
    private const CITY_NAMES = array('Paris', 'Rouen', 'Aix-en-Provence', 'Lile');

    private CityRepository $instance;

    public function setUp(): void
    {
        $this->instance = new CityRepository(dirname(__FILE__) . '/../../../../db/cities.csv');
    }

    public function testFetchByDepartmentId()
    {
        $this->assertInstanceOf(
            CityRepository::class,
            $this->instance
        );

        $yvelinesCities = $this->instance->fetchByDepartmentId(self::YVELINES_ID);
        $versailles = false;
        foreach ($yvelinesCities as $city) {
            if ($city->getName() === self::YVELINE_MAIN_CITY) {
                $versailles = true;
            }
        }
        $this->assertEquals(true, $versailles);

        $nbYvelinesCities = count($yvelinesCities);
        $this->assertEquals(262, $nbYvelinesCities);
    }

    public function testGetLastModified()
    {
        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $this->instance->getLastModified()
        );
    }

    public function testGetAll()
    {
        $this->assertInstanceOf(
            Traversable::class,
            $this->instance->getAll()
        );

        foreach ($this->instance->getAll() as $item) {
            $firstItem = $item;
            break;
        }
        $this->assertInstanceOf(
            City::class,
            $firstItem
        );
    }

    public function testAlphabeticalSorting()
    {
        $data = [];
        $i = 1;
        foreach (self::CITY_NAMES as $name) {
            $city = new City();
            $city->setId($i);
            $city->setName($name);
            $data[] = $city;
            $i++;
        }

        $this->assertIsArray($this->instance->alphabeticalSorting($data));

        $result = $this->instance->alphabeticalSorting($data);

        $this->assertEquals(self::CITY_NAMES[2], $result[0]->getName());
        $this->assertEquals(self::CITY_NAMES[1], $result[3]->getName());
    }
}