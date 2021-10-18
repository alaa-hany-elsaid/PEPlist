<?php

namespace Alaa\PEPlist\Types;

use Alaa\PEPlist\Exceptions\PlistException;

trait Inserter
{


    /**
     * @param $params
     * @throws PlistException
     */
    private  function inserter($params){
        if (!is_object($params['NV|Node'])) {
            $this->node->appendChild(PrimitiveType::createNewPrimitiveElement($params['pType'] ?? 'string', $params['NV|Node'])->getNode());
            return ;
        } elseif ( $params['NV|Node'] instanceof  AbstractType) {
            $this->node->appendChild($params['NV|Node']->getNode());
            return ;

        }elseif( $params['NV|Node'] instanceof  \DOMNode){
            $this->node->appendChild($params['NV|Node']);
            return ;
        }
        throw  new PlistException("Unknown Object Type ");
    }
}