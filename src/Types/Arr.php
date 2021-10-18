<?php

namespace Alaa\PEPlist\Types;

use Alaa\PEPlist\Exceptions\PlistException;
use DOMNode;
use Tightenco\Collect\Support\Collection;

class Arr extends AbstractType implements NonPrimitiveTypes
{

    use Inserter;

    /**
     * @param DOMNode $node
     */
    public function __construct(DOMNode $node)
    {
        parent::__construct($node);
    }


    /**
     * create empty array
     * @return Arr
     */
    public static function createEmptyArr(): Arr
    {
        return TypeFactory::factory(
            static::$globalDocument->createElement("array")
        );
    }


    /**
     * @param array $params
     * @return Arr
     * @throws PlistException
     */
    public function insert(array $params): Arr
    {
        $params = $this->checkArrParameters($params);
        $this->inserter($params);
        return $this;
    }

    /**
     * update value
     * params [index => "start from 0" , 'NV|Node' => value or DOMNode]
     *
     * @param array $params
     * @return bool
     * @throws PlistException
     */
    public function edit(array $params): bool
    {

        $params = $this->checkArrParameters($params);
        if ($node = $this->getChildren()->where("index", $params['index'])->first()) {
            if (!is_object($params['NV|Node'])) {
                $node['node']->getNode()->nodeValue = $params['NV|Node'];
                return true;
            } elseif ($node = (new \DOMXPath(static::$globalDocument))->query($node["node"]->getNode()->getNodePath())->item(0)) {
                if ($params['NV|Node'] instanceof AbstractType) {
                    $node->parentNode->replaceChild($params["NV|Node"]->getNode(), $node);
                    return true;
                } elseif ($params['NV|Node'] instanceof DOMNode) {
                    $node->parentNode->replaceChild($params["NV|Node"], $node);
                    return true;
                }
                throw  new PlistException("Unknown Object Type ");
            }

        }
        return false;
    }


    /**
     * edit by index if not found insert new DOMNode
     * @param array $params
     * @return Arr
     * @throws PlistException
     */
    public function editOrCreate(array $params): Arr
    {
        if (!$this->edit($params)) {
            $this->insert($params);
        }
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function checkParameters(array $params): void
    {
        if (!isset($params["NV|Node"])) {
            throw new PlistException("please set key and NV|Node ", 104);
        }
    }


    /**
     * @inheritDoc
     */
    public function getLength(): int
    {
        return $this->getNode()->childNodes->length;
    }

    /**
     * @inheritDoc
     */
    public function getChildren(): Collection
    {
        $collect = new Collection();
        foreach ($this->node->childNodes as $index => $node) {
            $collect->add(["index" => $index, "node" => (TypeFactory::factory($node))]);
        }
        return $collect;
    }


    /**
     *  check for custom parameters
     * @param array $params
     * @return array
     * @throws PlistException
     */
    private function checkArrParameters(array $params) : array
    {
        $this->checkParameters($params);
        if (!isset($params['index'])) $params['index'] = $this->getLength();
        return $params;
    }
}