<?php

namespace Alaa\PEPlist\Types;

use DOMDocument;
use DOMNode;

abstract class AbstractType
{

    protected static ?DOMDocument $globalDocument;
    protected DOMNode $node;

    /**
     * get abstract type with default document or custom document
     * @param DOMNode $node
     */
    public function __construct(DOMNode $node)
    {
        $this->node = $node;
    }


    /**
     * set default document
     * it will after create plist every time
     * @param DOMDocument $globalDocument
     */
    public static function  setGlobalDocument( DOMDocument $globalDocument){
        static::$globalDocument = $globalDocument;
    }


    /**
     * @return DOMNode
     */
    public function getNode(): DOMNode
    {
        return $this->node;
    }


    /**
     * get Current Global DOMDocument
     * @return DOMDocument|null
     */
    public static function getGlobalDocument(): ?DOMDocument
    {
        return self::$globalDocument;
    }

}