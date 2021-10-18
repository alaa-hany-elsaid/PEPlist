#PEPlist
##Property List manipulation library with Php
 ** **



### Usage
** **
__Install with Composer__

```Bash
    composer require alaa/peplist
```

** **
```PHP
    use Alaa\PEPlist\Plist;
    use \Alaa\PEPlist\Types\PrimitiveType;
    //$plist = new Plist("<content> </content>" , "path to save (optional)");
    // or 
    //$plist =   Plist::fromFile("PATH/TO/File");
    // or
    $plist =   Plist::createNew();
    
    var_dump($plist->getRootDict()->getLength());
    var_dump($plist->getRootDict()->getChildren());
    /*
     * NV|Node
     * can be
     * 1 ) non object , but should set pType string , data , date if not default is string
     * 2) AbstractType object 
     * 3) DOMNode
     */
    $plist->getRootDict()
    ->insert(["key" => "keyExample" , "NV|Node" => PrimitiveType::createNewPrimitiveElement("string" , "i am string and this my value")])
    ->insert(["key" => "newKey2" , "NV|Node" => "value" , "pType" => "string" ])
    ->insert([
         "key" => "new Arr" ,
         "NV|Node" => \Alaa\PEPlist\Types\Arr::createEmptyArr()->insert(["NV|Node" => "test" ])]);
    $plist->savePretty("test.plist");

```
#### **Output(test.plist)**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
	<dict>
		<key>keyExample</key>
		<string>i am string and this my value</string>
		<key>newKey2</key>
		<string>value</string>
		<key>new Arr</key>
		<array>
			<string>test</string>
		</array>
	</dict>
</plist>
```

** **

###License
> **MIT**
