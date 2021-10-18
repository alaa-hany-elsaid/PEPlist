<?php

namespace Alaa\PEPlist\Types;

use DOMDocument;
use DOMNode;

class TypeFactory
{


    /**
     * @param DOMNode $DOMNode
     */
    public static function factory(DOMNode $DOMNode )
    {
        if($DOMNode->nodeName === "dict"){
            return (new Dict($DOMNode ));
        }elseif($DOMNode->nodeName === "array"){
            return (new Arr($DOMNode ));
        }
        else
            return ( new PrimitiveType( $DOMNode ));
    }

}