<?php

/**
 * Test suite for Driver class.
 */

namespace Test;

use App\Driver;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class DriverTest
 * @package Test
 */
class DriverTest extends TestCase
{
    /**
     * Tests gossips.
     *
     * @dataProvider gossipProvider
     *
     * @param $driverGossips
     * @param $allGossips
     * @param $expected
     */
    public function testDriverGossips($driverGossips, $allGossips, $expected)
    {
        $driver = new Driver([0], '');
        $driver->learnGossips($driverGossips);
        if ($expected) {
            $this->assertTrue($driver->knowsAllGossips($allGossips));
        } else {
            $this->assertFalse($driver->knowsAllGossips($allGossips));
        }
    }

    /**
     * Tests driver's stops and driving to next one on the route.
     *
     * @dataProvider stopsProvider
     *
     * @param $route
     * @param $nextStops
     * @param $expected
     */
    public function testDriverStops($route, $nextStops, $expected)
    {
        $driver = new Driver($route, '12345');
        for ($i = 0; $i < $nextStops; $i++) {
            $driver->nextStop();
        }
        $this->assertEquals($expected, $driver->getCurrentStop());
    }

    /**
     * DataProvider for testing gossips, provides gossips initially known to driver,
     * list of all gossips and expected information, if driver knows everything.
     *
     * @return Generator
     */
    public function gossipProvider()
    {
        yield 'one driver, one gossip' => [
            'driverGossips' => ['12345'],
            'allGossips' => ['12345'],
            'expected' => true
        ];
        yield 'one driver, two gossips' => [
            'driverGossips' => ['qwerty'],
            'allGossips' => ['12345', 'qwerty'],
            'expected' => false
        ];
        yield 'one driver, learned gossips' => [
            'driverGossips' => ['12345', 'qwerty'],
            'allGossips' => ['12345', 'qwerty'],
            'expected' => true
        ];
    }

    /**
     * Provides data for testing stops. Driver's route,
     * number of stops to step and expected current stop at the moment.
     *
     * @return Generator
     */
    public function stopsProvider()
    {
        yield 'first stop' => [
            'route' => [1, 2],
            'nextStops' => 0,
            'expected' => 1
        ];
        yield 'one next stop' => [
            'route' => [1, 2],
            'nextStops' => 1,
            'expected' => 2
        ];
        yield 'wrap route' => [
            'route' => [1, 2, 3],
            'nextStops' => 3,
            'expected' => 1
        ];
    }
}
