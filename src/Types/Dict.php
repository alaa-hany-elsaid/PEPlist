<?php

namespace Alaa\PEPlist\Types;


use Alaa\PEPlist\Exceptions\PlistException;

use DOMNode;
use Tightenco\Collect\Support\Collection;

class Dict extends AbstractType implements NonPrimitiveTypes
{
    use Inserter;

     public function __construct(DOMNode $node)
    {
        parent::__construct($node);
    }


    /**
     */
    public static function createEmptyDict() : Dict
    {
        return (TypeFactory::factory(static::$globalDocument->createElement("dict")))  ;
    }


    /**
     *insert new type
     *params [ key  , 'NV|Node' =>  primitive value or DOMNode , 'pType' => if 'NV|Node' is primitive set type (if not set default is string)]
     * @param array $params
     * @return Dict
     * @throws PlistException
     */
    public function insert(array $params): Dict
    {
        $this->checkParameters($params);
        $this->node->appendChild(   static::$globalDocument->createElement("key", $params['key']));
        $this->inserter($params);
        return $this;
    }

    /**
     * @param array $params
     * @return bool
     * @throws PlistException
     */
    public function edit(array $params): bool
    {
        $this->checkParameters($params);
        if ($node = $this->getChildren()->where("key", $params["key"])->first()) {
            if ($node = (new \DOMXPath(static::$globalDocument))->query($node["node"]->getNode()->getNodePath())->item(0)) {
                if (!is_object($params["NV|Node"])) {
                    $node['node']->getNode()->nodeValue = $params['NV|Node'];
                } else  {
                    if ($node = (new \DOMXPath(static::$globalDocument))->query($node["node"]->getNode()->getNodePath())->item(0)) {
                        if ( $params['NV|Node'] instanceof  AbstractType) {
                            $node->parentNode->replaceChild($params["NV|Node"]->getNode(), $node);
                            return true;
                        }elseif( $params['NV|Node'] instanceof  DOMNode){
                            $node->parentNode->replaceChild($params["NV|Node"], $node);
                            return true;
                        }

                    }
                }
                throw  new PlistException("Unknown Object Type ");
            }
        }
        return false;
    }


    /**
     * @param array $params
     * @return bool
     * @throws PlistException
     */
    public function editOrCreate(array $params): Dict
    {
        if (!$this->edit($params)) {
            $this->insert($params);
        }
        return $this;
    }

    public function getLength(): int
    {
        return ($this->node->childNodes->length / 2);
    }

    public function getChildren(): Collection
    {
        $collect = new Collection();
        $childNodes = $this->node->childNodes;
        for ($i = 0; $i < $childNodes->length; $i++) {
            if ((get_class($childNodes->item($i)) === "DOMElement") && ($childNodes->item($i)->nodeName === "key")) {
                $collect->add(["key" => $childNodes->item($i)->nodeValue, "node" => TypeFactory::factory($childNodes->item(++$i))]);
            }
        }
        return $collect;
    }


    /**
     * @param array $params
     * @throws PlistException
     */
    public function checkParameters(array $params): void
    {
        if (!isset($params["key"]) || !isset($params["NV|Node"])) {
            throw new PlistException("please set key and NV|Node ", 104);
        }
    }
}