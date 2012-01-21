<?php

namespace DavidBadura\FixturesBundle\RelationManager;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Repository implements RepositoryInterface
{

    protected $objects = array();

    public function get($key)
    {
        if (!$this->has($key)) {

        }
        return $this->objects[$key];
    }

    public function has($key)
    {
        return isset($this->objects[$key]);
    }

    public function set($key, $object)
    {
        if ($this->has($key)) {

        }
        $this->objects[$key] = $object;
    }

    public function count()
    {
        return count($this->objects);
    }

}