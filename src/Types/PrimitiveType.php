<?php

namespace Alaa\PEPlist\Types;

use Alaa\PEPlist\Exceptions\PlistException;
use DOMDocument;
use DOMElement;
use DOMNode;

class PrimitiveType extends AbstractType
{
    protected static array $types = [
        'string',
        'real',
        'integer',
        'true',
        'false',
        'date',
        'data'
    ];

    /**
     * @param DOMNode $node
     */
    public function __construct(DOMNode $node)
    {
        parent::__construct($node);
    }


    /**
     * @param string $nodeName
     * @param string $nodeValue
     * @return PrimitiveType
     * @throws PlistException
     */
    public static function createNewPrimitiveElement( string $nodeName, string $nodeValue ): PrimitiveType
    {
        if (in_array($nodeName, PrimitiveType::$types)) {
            if ($node = static::$globalDocument->createElement($nodeName, $nodeValue)){
                return    (new PrimitiveType($node)) ;
            }
        }
        throw (new PlistException("this type is not support $nodeName ", 105));
    }
}