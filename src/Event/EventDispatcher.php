<?php
declare(strict_types=1);

namespace CommandEventApi\Event;

class EventDispatcher
{
    const LISTEN_ALL = '_all_';

    /** @var callable[][] */
    private $listeners = [];

    /** @var mixed[] */
    private $events = [];

    public function listen(string $eventClass, callable $listener)
    {
        $this->listeners[$eventClass][] = $listener;
    }

    public function stopListen(string $eventClass, callable $listener)
    {
        $key = array_search($listener, $this->listeners[$eventClass], true);

        if ($key !== false) {
            unset($this->listeners[$eventClass][$key]);
        }
    }

    public function listenAll(callable $listener)
    {
        $this->listen(self::LISTEN_ALL, $listener);
    }

    public function stopListenAll(callable $listener)
    {
        $this->stopListen(self::LISTEN_ALL, $listener);
    }

    public function addEvent($event)
    {
        $this->events[] = $event;
    }

    public function getAllEvents(): array
    {
        return $this->events;
    }

    public function cleanEvents()
    {
        $this->events = [];
    }

    public function dispatchAll()
    {
        foreach ($this->events as $event) {
            $this->dispatch($event);
        }

        $this->cleanEvents();
    }

    public function dispatch($event)
    {
        $eventClass = get_class($event);

        foreach ($this->listeners[$eventClass] ?? [] as $listener) {
            $listener($event);
        }

        foreach ($this->listeners[self::LISTEN_ALL] ?? [] as $listener) {
            $listener($event);
        }
    }
}
