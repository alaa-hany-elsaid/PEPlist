<?php

namespace Alaa\PEPlist;

use Alaa\PEPlist\Exceptions\PlistException;

class DOMDocumentsContainer implements \ArrayAccess
{

    private array $container = [];

    public function NewEmptyDomDocument(string $name, string $plistVersion = "1.0", string $encoding = 'UTF-8'): Plist
    {
        $this->container[$name] = Plist::createNew($plistVersion, $encoding);
        return $this->offsetGet($name);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return (isset($this->container[$offset]));
    }

    /**
     * @param mixed $offset
     * @return mixed
     * @throws PlistException
     */
    public function offsetGet($offset): Plist
    {
        if ($this->offsetExists($offset))
            return $this->container[$offset];

        throw new PlistException("DOMDocument Not found");
    }

    public function offsetSet($offset, $value)
    {
        $this->container[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
}