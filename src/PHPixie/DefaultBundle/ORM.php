<?php

namespace PHPixie\DefaultBundle;

/**
 * Here you can define wrappers for the ORM to use.
 */
class ORM extends \PHPixie\ORM\Wrappers\Implementation
{
    /**
     * @var Builder
     */
    protected $builder;
    protected $repositoryMap = array();
    protected $queryMap      = array();
    protected $entityMap     = array();
    protected $embeddedMap   = array();

    /**
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;

        $this->databaseRepositories = array_merge(
            $this->databaseRepositories,
            array_keys($this->repositoryMap)
        );

        $this->databaseQueries = array_merge(
            $this->databaseQueries,
            array_keys($this->queryMap)
        );

        $this->databaseEntities = array_merge(
            $this->databaseEntities,
            array_keys($this->entityMap)
        );

        $this->embeddedEntities = array_merge(
            $this->embeddedEntities,
            array_keys($this->embeddedMap)
        );
    }

    public function databaseRepositoryWrapper($repository)
    {
        if($wrapped = $this->classMapWrapper($repository, $this->repositoryMap)) {
            return $wrapped;
        }

        return parent::databaseRepositoryWrapper($repository);
    }

    public function databaseQueryWrapper($query)
    {
        if($wrapped = $this->classMapWrapper($query, $this->queryMap)) {
            return $wrapped;
        }

        return parent::databaseQueryWrapper($query);
    }

    public function databaseEntityWrapper($entity)
    {
        if($wrapped = $this->classMapWrapper($entity, $this->entityMap)) {
            return $wrapped;
        }

        return parent::databaseEntityWrapper($entity);
    }

    public function embeddedEntityWrapper($entity)
    {
        if($wrapped = $this->classMapWrapper($entity, $this->embeddedMap)) {
            return $wrapped;
        }

        return parent::embeddedEntityWrapper($entity);
    }

    protected function classMapWrapper($item, $classMap)
    {
        $name = $item->modelName();
        if(!isset($classMap[$name])) {
            return null;
        }

        $class = $classMap[$item];
        return new $class($item, $this->builder);
    }
}