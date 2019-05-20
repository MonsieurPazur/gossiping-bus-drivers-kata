<?php

/**
 * Class responsible of handling a collection of drivers.
 */

namespace App;

/**
 * Class DriverManager
 * @package App
 */
class DriverManager
{
    /**
     * @var int maximum amount of stops when calculating minimum
     */
    const MAX_STOPS = 480;

    /**
     * @var string information to output, when it's not possible for all drivers to know all gossips
     */
    const MINIMUM_NEVER = 'never';

    /**
     * @var Driver[] $drivers list of all drivers
     */
    private $drivers;

    /**
     * @var int $stops current number of stops that took place
     */
    private $stops;

    /**
     * @var array $gossips list of all gossips
     */
    private $gossips;

    /**
     * DriverManager constructor.
     */
    public function __construct()
    {
        $this->drivers = [];
        $this->stops = 0;
        $this->gossips = [];
    }

    /**
     * Adds new driver (with given route) to this manager.
     *
     * @param array $route stops that this driver will stop at
     * @param string $gossip unique gossip that's initially known only to this driver
     */
    public function addDriver(array $route, string $gossip): void
    {
        $this->drivers[] = new Driver($route, $gossip);
        $this->gossips[] = $gossip;
    }

    /**
     * Calculates minimum amount of stops for all drivers to learn all gossips,
     * or 'never' when it's not possible within one day.
     *
     * @return int|string minimum number of stops or 'never'
     */
    public function getMinimumStops()
    {
        $this->stops = 0;
        for ($i = 0; $i < self::MAX_STOPS; $i++) {
            $this->stops++;
            $this->handleStop();
            if ($this->driversKnowAllGossips()) {
                return $this->stops;
            }
            $this->nextStop();
        }
        return self::MINIMUM_NEVER;
    }

    /**
     * Drives all drivers to next stop on their routes.
     */
    private function nextStop(): void
    {
        foreach ($this->drivers as $driver) {
            $driver->nextStop();
        }
    }

    /**
     * Checks whether all drivers know all gossips
     *
     * @return bool true if all drivers know all gossips
     */
    private function driversKnowAllGossips(): bool
    {
        foreach ($this->drivers as $driver) {
            if (!$driver->knowsAllGossips($this->gossips)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Handles current stop, checks whether any two drivers are on the same stop
     */
    private function handleStop(): void
    {
        for ($i = 0; $i < count($this->drivers); $i++) {
            for ($j = $i + 1; $j < count($this->drivers); $j++) {
                if ($this->drivers[$i]->getCurrentStop() === $this->drivers[$j]->getCurrentStop()) {
                    $this->exchangeGossips($this->drivers[$i], $this->drivers[$j]);
                }
            }
        }
    }

    /**
     * Exchange gossips between two drivers.
     *
     * @param Driver $one first driver
     * @param Driver $two second driver
     */
    private function exchangeGossips(Driver $one, Driver $two): void
    {
        $one->learnGossips($two->getGossips());
        $two->learnGossips($one->getGossips());
    }
}
