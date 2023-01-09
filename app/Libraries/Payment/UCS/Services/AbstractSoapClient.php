<?php


namespace App\Libraries\Payment\UCS\Services;

use SoapClient,
    InvalidArgumentException;

abstract class AbstractSoapClient extends SoapClient
{
    protected static array $classMap;

    public function __construct($wsdl = null, $options = array()) {
        if (empty(static::$classMap)) {
            throw new InvalidArgumentException('Не задан $classMap SOAP сервиса');
        }

        foreach (static::$classMap as $wsdlClassName => $phpClassName) {
            if(!isset($options['classmap'][$wsdlClassName])) {
                $options['classmap'][$wsdlClassName] = $phpClassName;
            }
        }

        $options['cache_wsdl'] = WSDL_CACHE_NONE;

        parent::__construct($wsdl, $options);
    }

    /**
     * Checks if an argument list matches against a valid argument type list
     * @param array $arguments The argument list to check
     * @param array $validParameters A list of valid argument types
     * @return boolean true if arguments match against validParameters
     * @throws InvalidArgumentException invalid function signature message
     */
    protected function checkArguments($arguments, $validParameters) {
        $variables = "";
        foreach ($arguments as $arg) {
            $type = gettype($arg);
            if ($type == "object") {
                $type = get_class($arg);
            }
            $variables .= "(".$type.")";
        }

        if (!in_array($variables, $validParameters)) {
            throw new InvalidArgumentException("Invalid parameter types: ".str_replace(")(", ", ", $variables));
        }
        return true;
    }
}
