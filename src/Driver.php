<?php

/**
 * Holds information about driver, his route and known gossips.
 */

namespace App;

/**
 * Class Driver
 * @package App
 */
class Driver
{
    /**
     * @var array $route this driver`s stops
     */
    private $route;

    /**
     * @var int $currentStopIndex index ($this->route) of this driver's current stop
     */
    private $currentStopIndex;

    /**
     * @var array $knownGossips list of all gossips that this driver knows
     */
    private $knownGossips = [];

    /**
     * Driver constructor.
     *
     * @param array $route
     * @param string $gossip
     */
    public function __construct(array $route, string $gossip)
    {
        $this->route = $route;
        $this->currentStopIndex = 0;
        $this->knownGossips[] = $gossip;
    }

    /**
     * Checks whether this driver knows all gossips.
     *
     * @param array $gossips list of all gossips
     *
     * @return bool true if this driver knows all gossips
     */
    public function knowsAllGossips(array $gossips): bool
    {
        return array_diff($gossips, $this->knownGossips) === [];
    }

    /**
     * Adds new gossip to this driver's list of known gossips.
     *
     * @param array $gossips list of gossips to be learned
     */
    public function learnGossips(array $gossips): void
    {
        $this->knownGossips = array_unique(array_merge($this->knownGossips, $gossips));
    }

    /**
     * Gets this driver's current stop.
     *
     * @return int current stop
     */
    public function getCurrentStop(): int
    {
        return $this->route[$this->currentStopIndex];
    }

    /**
     * Moves this driver to his next stop.
     */
    public function nextStop(): void
    {
        $this->currentStopIndex = ++$this->currentStopIndex % count($this->route);
    }

    /**
     * Gets this driver's known gossips.
     *
     * @return array list of known gossips
     */
    public function getGossips(): array
    {
        return $this->knownGossips;
    }
}
