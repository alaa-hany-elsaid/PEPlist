<?php

namespace Alaa\PEPlist\Types;
use Alaa\PEPlist\Exceptions\PlistException;
use Exception;
use Tightenco\Collect\Support\Collection;

interface NonPrimitiveTypes
{

    public function insert(array $params):NonPrimitiveTypes;
    public function edit(array $params):bool;
    public function editOrCreate(array $params):NonPrimitiveTypes;

    /**
     * check for parameters
     * @param array $params
     * @throws PlistException
     */
    public function checkParameters(array $params):void;

    /**
     * get number of children
     * @return int
     */
    public function getLength():int;

    /**
     * get Children as collection
     * @return Collection
     */
    public function getChildren(): Collection;
}