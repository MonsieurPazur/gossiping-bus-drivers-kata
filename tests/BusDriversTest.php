<?php

/**
 * Basic test suite for checking bus driver gossips.
 */

namespace Test;

use App\DriverManager;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class BusDriversTest
 * @package Test
 */
class BusDriversTest extends TestCase
{
    /**
     * Tests handling drivers collection.
     *
     * @dataProvider routesProvider
     *
     * @param $drivers
     * @param $expected
     */
    public function testBusDrivers($drivers, $expected)
    {
        $manager = new DriverManager();
        foreach ($drivers as $driver) {
            $manager->addDriver($driver['route'], $driver['gossip']);
        }
        $this->assertEquals($expected, $manager->getMinimumStops());
    }

    /**
     * Provides data with a set of drivers (routes and gossips)
     * and expected minimum stops for each to learn all gossips.
     *
     * @return Generator
     */
    public function routesProvider()
    {
        yield 'one stop' => [
            'drivers' => [
                [
                    'route' => [0],
                    'gossip' => '12345'
                ],
                [
                    'route' => [0],
                    'gossip' => 'qwerty'
                ]
            ],
            'expected' => 1
        ];
        yield 'two stops' => [
            'drivers' => [
                [
                    'route' => [1, 2],
                    'gossip' => '12345'
                ],
                [
                    'route' => [3, 2],
                    'gossip' => 'qwerty'
                ]
            ],
            'expected' => 2
        ];
        yield 'three drivers' => [
            'drivers' => [
                [
                    'route' => [3, 1, 2, 3],
                    'gossip' => '12345'
                ],
                [
                    'route' => [3, 2, 3, 1],
                    'gossip' => 'qwerty'
                ],
                [
                    'route' => [4, 2, 3, 4, 5],
                    'gossip' => 'asdf'
                ]
            ],
            'expected' => 5
        ];
        yield 'never' => [
            'drivers' => [
                [
                    'route' => [2, 1, 2],
                    'gossip' => '12345'
                ],
                [
                    'route' => [5, 2, 8],
                    'gossip' => 'qwerty'
                ]
            ],
            'expected' => 'never'
        ];
    }
}
