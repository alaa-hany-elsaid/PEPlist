<?php

namespace Alaa\PEPlist;

use Alaa\PEPlist\Exceptions\PlistException;
use Alaa\PEPlist\Types\AbstractType;
use Alaa\PEPlist\Types\Dict;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Exception;
use PrettyXml\Formatter as PrettyFormat;

/**
 * Load or create plist
 */
class Plist
{


    private DOMDocument $DOMDOC;
    private ?Dict $dicRoot = null;
    private ?string $path;

    /**
     * load plist from content
     * @param string $content
     * @param string|null $path
     * @throws PlistException
     */
    public function __construct(string $content, string $path = null, DOMDocument $document = null)
    {

        if ($document == null) {
            $this->DOMDOC = (new DOMDocument());
            $this->DOMDOC->preserveWhiteSpace = false;
            $this->DOMDOC->formatOutput = true;
            if ($this->DOMDOC->loadXML($content) !== true) {
                throw new PlistException("Can't load plist please check file or content first", 101);
            }
            AbstractType::setGlobalDocument($this->DOMDOC);

            //        if (!$this->DOMDOC->validate()) {
//            throw new PlistException("Plist File is not Validated check it with " . static::class . "::check(\$xmlContent)", 102);
//        }
        } else {
            $this->DOMDOC = $document;
        }
        $this->path = $path;

    }

    /**
     * Load plist from file
     * @param string $plistPath
     * @return Plist
     * @throws Exception
     */
    public static function fromFile(string $plistPath): Plist
    {
        return (new self(file_get_contents($plistPath), $plistPath));
    }


    /**
     * create new plist
     * @param string $plistVersion
     * @param string $encoding
     * @return Plist
     */
    public static function createNew(string $plistVersion = "1.0", string $encoding = 'UTF-8'): Plist
    {
        return (new self(<<<"plistN"
<?xml version="$plistVersion" encoding="$encoding"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="$plistVersion">
<dict>
</dict>
</plist>
plistN
        ));

    }


    /**
     * get root Dict of plist
     * @return Dict
     * @throws PlistException
     */
    public function getRootDict(): Dict
    {
        if ($this->dicRoot !== null) {
            return $this->dicRoot;
        } elseif ($dictRoot = (new DOMXPath($this->DOMDOC))->query('/plist/dict')) {
            $this->dicRoot = new Dict($dictRoot->item(0), $this->DOMDOC);
            return $this->dicRoot;
        }
        throw  new PlistException("Dict root ot found ", 103);
    }


    /**
     * return plist node
     * @return DOMNode
     */
    public function getPlistNode(): DOMNode
    {
        return $this->DOMDOC->childNodes->item(1);
    }


    /**
     * return Document
     * @return DOMDocument
     */
    public function getDocument(): DOMDocument
    {
        return $this->DOMDOC;
    }


    /**
     * save Document with pretty format
     * @param string|null $filename
     * @throws PlistException
     */
    public function savePretty(?string $filename = null)
    {
        if (null == $filename) {
            $filename = $this->path;
        }
        $formatter = (new PrettyFormat());
        $formatter->setIndentCharacter("\t");
        $formatter->setIndentSize(1);
        file_put_contents($filename ?? "plistFile.plist", $formatter->format($this->DOMDOC->saveXML()));
    }


    /**
     * remove tmp file after kill object
     */
    public function __destruct()
    {
        if (file_exists("tmp_plist"))
            unlink("tmp_plist");
    }


    /**
     * @return Plist
     */
    public function setAsGlobalDocument(): Plist
    {
        AbstractType::setGlobalDocument($this->getDocument());
        return $this;
    }


    /**
     * @return Plist
     * @throws PlistException
     */
    public function deepCopy(): Plist
    {
        return (new self("", null, $this->getDocument()->cloneNode(true)));
    }


    /**
     * @return Plist
     * @throws PlistException
     */
    public function copy(): Plist
    {
        return (new self("", null, $this->getDocument()->cloneNode(false)));
    }
}