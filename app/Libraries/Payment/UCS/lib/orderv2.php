<?php

if (!class_exists("PaymentArray")) {
/**
 * PaymentArray
 */
class PaymentArray {
    /**
     * @access public
     * @var Payment[]
     */
    public $Payment;
}}

if (!class_exists("get_finished")) {
/**
 * get_finished
 */
class get_finished {
    /**
     * @access public
     * @var integer
     */
    public $shop_id;
    /**
     * @access public
     * @var dateTime
     */
    public $start;
    /**
     * @access public
     * @var dateTime
     */
    public $stop;
}}

if (!class_exists("OrderItem")) {
/**
 * OrderItem
 */
class OrderItem {
    /**
     * @access public
     * @var string
     */
    public $descr;
    /**
     * @access public
     * @var string
     */
    public $clearing;
    /**
     * @access public
     * @var Amount
     */
    public $amount;
    /**
     * @access public
     * @var string
     */
    public $number;
    /**
     * @access public
     * @var string
     */
    public $typename;
    /**
     * @access public
     * @var integer
     */
    public $quanity;
    /**
     * @access public
     * @var string
     */
    public $name;
    /**
     * @access public
     * @var string
     */
    public $host;
    /**
     * @access public
     * @var Taxes
     */
    public $taxes;
}}

if (!class_exists("stringArray")) {
/**
 * stringArray
 */
class stringArray {
    /**
     * @access public
     * @var string[]
     */
    public $string;
}}

if (!class_exists("cancelResponse")) {
/**
 * cancelResponse
 */
class cancelResponse {
    /**
     * @access public
     * @var OrderStatus
     */
    public $retval;
}}

if (!class_exists("Taxes")) {
/**
 * Taxes
 */
class Taxes {
    /**
     * @access public
     * @var tax[]
     */
    public $tax;
}}

if (!class_exists("tax")) {
/**
 * Taxes
 */
class tax {
    /**
     * @access public
     * @var string
     */
    public $percentage;
    /**
     * @access public
     * @var string
     */
    public $type;
     /**
     * @access public
     * @var Amount
     */
    public $amount;
}}

if (!class_exists("AttemptArray")) {
/**
 * AttemptArray
 */
class AttemptArray {
    /**
     * @access public
     * @var Attempt[]
     */
    public $Attempt;
}}

if (!class_exists("rejectResponse")) {
/**
 * rejectResponse
 */
class rejectResponse {
    /**
     * @access public
     * @var OrderStatus
     */
    public $retval;
}}

if (!class_exists("Attempt")) {
/**
 * Attempt
 */
class Attempt {
    /**
     * @access public
     * @var string
     */
    public $ip;
    /**
     * @access public
     * @var Amount
     */
    public $amount;
    /**
     * @access public
     * @var Error
     */
    public $error;
    /**
     * @access public
     * @var dateTime
     */
    public $date;
    /**
     * @access public
     * @var string
     */
    public $type;
    /**
     * @access public
     * @var string
     */
    public $id;
}}

if (!class_exists("get_status")) {
/**
 * get_status
 */
class get_status {
    /**
     * @access public
     * @var OrderID
     */
    public $order;
}}

if (!class_exists("OnlineResult")) {
/**
 * OnlineResult
 */
class OnlineResult {
    /**
     * @access public
     * @var string
     */
    public $session;
    /**
     * @access public
     * @var string
     */
    public $redirect_url;
}}

if (!class_exists("cancel")) {
/**
 * cancel
 */
class cancel {
    /**
     * @access public
     * @var OrderID
     */
    public $order;
}}

if (!class_exists("confirmResponse")) {
/**
 * confirmResponse
 */
class confirmResponse {
    /**
     * @access public
     * @var OrderStatus
     */
    public $retval;
}}

if (!class_exists("OrderStatus")) {
/**
 * OrderStatus
 */
class OrderStatus {
    /**
     * @access public
     * @var string
     */
    public $status;
    /**
     * @access public
     * @var AttemptArray
     */
    public $attempts;
    /**
     * @access public
     * @var CardInfoArray
     */
    public $details;
    /**
     * @access public
     * @var string
     */
    public $shopref;
    /**
     * @access public
     * @var OrderID
     */
    public $order;
    /**
     * @access public
     * @var PaymentArray
     */
    public $payments;
}}

if (!class_exists("Amount")) {
/**
 * Amount
 */
class Amount {
    /**
     * @access public
     * @var string
     */
    public $currency;
    /**
     * @access public
     * @var double
     */
    public $amount;
}}

if (!class_exists("Document")) {
/**
 * Document
 */
class Document {
    /**
     * @access public
     * @var string
     */
    public $code;
    /**
     * @access public
     * @var string
     */
    public $holder;
    /**
     * @access public
     * @var string
     */
    public $number;
}}

if (!class_exists("confirm")) {
/**
 * confirm
 */
class confirm {
    /**
     * @access public
     * @var OrderID
     */
    public $order;
    /**
     * @access public
     * @var string
     */
    public $payment_id;
}}

if (!class_exists("register_depositResponse")) {
/**
 * register_depositResponse
 */
class register_depositResponse {
    /**
     * @access public
     * @var OnlineResult
     */
    public $retval;
}}

if (!class_exists("PostEntryArray")) {
/**
 * PostEntryArray
 */
class PostEntryArray {
    /**
     * @access public
     * @var PostEntry[]
     */
    public $PostEntry;
}}

if (!class_exists("Error")) {
/**
 * Error
 */
class Error {
    /**
     * @access public
     * @var string
     */
    public $category;
    /**
     * @access public
     * @var string
     */
    public $code;
}}

if (!class_exists("OrderItemArray")) {
/**
 * OrderItemArray
 */
class OrderItemArray {
    /**
     * @access public
     * @var OrderItem[]
     */
    public $OrderItem;
}}

if (!class_exists("PaymentClass")) {
/**
 * Payment
 */
class PaymentClass {
    /**
     * @access public
     * @var string
     */
    public $authorg;
    /**
     * @access public
     * @var string
     */
    public $authcode;
    /**
     * @access public
     * @var string
     */
    public $salepoint;
    /**
     * @access public
     * @var string
     */
    public $clearing;
    /**
     * @access public
     * @var Amount
     */
    public $amount;
    /**
     * @access public
     * @var Document
     */
    public $doc;
    /**
     * @access public
     * @var dateTime
     */
    public $date;
    /**
     * @access public
     * @var string
     */
    public $type;
    /**
     * @access public
     * @var string
     */
    public $id;
}}


if (!class_exists("register_online")) {
/**
 * register_online
 */
class register_online {
    /**
     * @access public
     * @var OrderID
     */
    public $order;
    /**
     * @access public
     * @var Amount
     */
    public $cost;
    /**
     * @access public
     * @var CustomerInfo
     */
    public $customer;
    /**
     * @access public
     * @var OrderInfo
     */
    public $description;
    /**
     * @access public
     * @var PostEntryArray
     */
    public $postdata;
}}



if (!class_exists("register_deposit")) {
/**
 * register_deposit
 */
class register_deposit {
    /**
     * @access public
     * @var OrderID
     */
    public $order;
    /**
     * @access public
     * @var Amount
     */
    public $cost;
    /**
     * @access public
     * @var CustomerInfo
     */
    public $customer;
    /**
     * @access public
     * @var OrderInfo
     */
    public $description;
    /**
     * @access public
     * @var DepositInfo
     */
    public $account;
    /**
     * @access public
     * @var PostEntryArray
     */
    public $postdata;
}}

if (!class_exists("OrderInfo")) {
/**
 * OrderInfo
 */
class OrderInfo {
    /**
     * @access public
     * @var OrderItemArray
     */
    public $items;
    /**
     * @access public
     * @var string
     */
    public $shopref;
    /**
     * @access public
     * @var string
     */
    public $paytype;
    /**
     * @access public
     * @var string
     */
    public $descr;
    /**
     * @access public
     * @var dateTime
     */
    public $timelimit;
}}

if (!class_exists("get_finishedResponse")) {
/**
 * get_finishedResponse
 */
class get_finishedResponse {
    /**
     * @access public
     * @var OrderStatusArray
     */
    public $retval;
}}

if (!class_exists("CardInfo")) {
/**
 * CardInfo
 */
class CardInfo {
    /**
     * @access public
     * @var string
     */
    public $product;
    /**
     * @access public
     * @var string
     */
    public $code;
    /**
     * @access public
     * @var string
     */
    public $secure;
    /**
     * @access public
     * @var string
     */
    public $country;
    /**
     * @access public
     * @var string
     */
    public $number;
    /**
     * @access public
     * @var string
     */
    public $holder;
    /**
     * @access public
     * @var integer
     */
    public $id;
    /**
     * @access public
     * @var string
     */
    public $bank;
}}

if (!class_exists("reject")) {
/**
 * reject
 */
class reject {
    /**
     * @access public
     * @var OrderID
     */
    public $order;
    /**
     * @access public
     * @var string
     */
    public $payment_id;
}}

if (!class_exists("OrderStatusArray")) {
/**
 * OrderStatusArray
 */
class OrderStatusArray {
    /**
     * @access public
     * @var OrderStatus[]
     */
    public $OrderStatus;
}}

if (!class_exists("CardInfoArray")) {
/**
 * CardInfoArray
 */
class CardInfoArray {
    /**
     * @access public
     * @var CardInfo[]
     */
    public $CardInfo;
}}

if (!class_exists("DepositInfo")) {
/**
 * DepositInfo
 */
class DepositInfo {
    /**
     * @access public
     * @var string
     */
    public $salepoint;
    /**
     * @access public
     * @var string
     */
    public $user;
    /**
     * @access public
     * @var string
     */
    public $agreement;
}}

if (!class_exists("refund")) {
/**
 * refund
 */
class refund {
    /**
     * @access public
     * @var OrderID
     */
    public $order;
    /**
     * @access public
     * @var string
     */
    public $payment_id;
    /**
     * @access public
     * @var Amount
     */
    public $cost;
    /**
     * @access public
     * @var string
     */
    public $refund_id;
}}

if (!class_exists("OrderID")) {
/**
 * OrderID
 */
class OrderID {
    /**
     * @access public
     * @var integer
     */
    public $shop_id;
    /**
     * @access public
     * @var string
     */
    public $number;
}}

if (!class_exists("register_offline")) {
/**
 * register_offline
 */
class register_offline {
    /**
     * @access public
     * @var OrderID
     */
    public $order;
    /**
     * @access public
     * @var Amount
     */
    public $cost;
    /**
     * @access public
     * @var CustomerInfo
     */
    public $customer;
    /**
     * @access public
     * @var OrderInfo
     */
    public $description;
}}

if (!class_exists("CustomerInfo")) {
/**
 * CustomerInfo
 */
class CustomerInfo {
    /**
     * @access public
     * @var string
     */
    public $id;
    /**
     * @access public
     * @var string
     */
    public $phone;
    /**
     * @access public
     * @var string
     */
    public $name;
    /**
     * @access public
     * @var string
     */
    public $email;
}}

if (!class_exists("PostEntry")) {
/**
 * PostEntry
 */
class PostEntry {
    /**
     * @access public
     * @var string
     */
    public $name;
    /**
     * @access public
     * @var string
     */
    public $value;
}}

if (!class_exists("register")) {
/**
 * register
 */
class register {
    /**
     * @access public
     * @var OrderID
     */
    public $order;
    /**
     * @access public
     * @var Amount
     */
    public $cost;
    /**
     * @access public
     * @var CustomerInfo
     */
    public $customer;
    /**
     * @access public
     * @var OrderInfo
     */
    public $description;
    /**
     * @access public
     * @var PostEntryArray
     */
    public $postdata;
}}

if (!class_exists("get_statusResponse")) {
/**
 * get_statusResponse
 */
class get_statusResponse {
    /**
     * @access public
     * @var OrderStatus
     */
    public $retval;
}}

if (!class_exists("register_onlineResponse")) {
/**
 * register_onlineResponse
 */
class register_onlineResponse {
    /**
     * @access public
     * @var OnlineResult
     */
    public $retval;
}}

if (!class_exists("refundResponse")) {
/**
 * refundResponse
 */
class refundResponse {
    /**
     * @access public
     * @var OrderInfo
     */
    public $retval;
}}

if (!class_exists("register_offlineResponse")) {
/**
 * register_offlineResponse
 */
class register_offlineResponse {
    /**
     * @access public
     * @var OfflineResult
     */
    public $retval;
}}

if (!class_exists("orderv2")) {
/**
 * orderv2
 * @author WSDLInterpreter
 */
class orderv2 extends SoapClient {
    /**
     * Default class map for wsdl=>php
     * @access private
     * @var array
     */
    private static $classmap = array(
        "PaymentArray" => "PaymentArray",
        "get_finished" => "get_finished",
        "OrderItem" => "OrderItem",
        "stringArray" => "stringArray",
        "cancelResponse" => "cancelResponse",
        "AttemptArray" => "AttemptArray",
        "rejectResponse" => "rejectResponse",
        "Attempt" => "Attempt",
        "get_status" => "get_status",
        "OnlineResult" => "OnlineResult",
        "cancel" => "cancel",
        "confirmResponse" => "confirmResponse",
        "OrderStatus" => "OrderStatus",
        "Amount" => "Amount",
        "Document" => "Document",
        "confirm" => "confirm",
        "PostEntryArray" => "PostEntryArray",
        "Error" => "Error",
        "OrderItemArray" => "OrderItemArray",
        "Payment" => "PaymentClass",
        "register_deposit" => "register_deposit",
        "OrderInfo" => "OrderInfo",
        "get_finishedResponse" => "get_finishedResponse",
        "CardInfo" => "CardInfo",
        "reject" => "reject",
        "OrderStatusArray" => "OrderStatusArray",
        "CardInfoArray" => "CardInfoArray",
        "refund" => "refund",
        "OrderID" => "OrderID",
        "CustomerInfo" => "CustomerInfo",
        "PostEntry" => "PostEntry",
        "register" => "register",
        "register_online" => "register_online",
        "get_statusResponse" => "get_statusResponse",
        "register_onlineResponse" => "register_onlineResponse",
        "refundResponse" => "refundResponse",
        "Taxes" => "Taxes",
        "tax" => "tax",
    );

    /**
     * Constructor using wsdl location and options array
     * @param string $wsdl WSDL location for this service
     * @param array $options Options for the SoapClient
     */
    public function __construct($wsdl=null, $options=array()) {
        foreach(self::$classmap as $wsdlClassName => $phpClassName) {
            if(!isset($options['classmap'][$wsdlClassName])) {
                $options['classmap'][$wsdlClassName] = $phpClassName;
            }
        }
        parent::__construct($wsdl, $options);
    }

    /**
     * Checks if an argument list matches against a valid argument type list
     * @param array $arguments The argument list to check
     * @param array $validParameters A list of valid argument types
     * @return boolean true if arguments match against validParameters
     * @throws Exception invalid function signature message
     */
    public function _checkArguments($arguments, $validParameters) {
        $variables = "";
        foreach ($arguments as $arg) {
            $type = gettype($arg);
            if ($type == "object") {
                $type = get_class($arg);
            }
            $variables .= "(".$type.")";
        }

        if (!in_array($variables, $validParameters)) {
            throw new Exception("Invalid parameter types: ".str_replace(")(", ", ", $variables));
        }
        return true;
    }

    /**
     * Service Call: cancel
     * Parameter options:
     * (cancel) cancel
     * @param mixed,... See function description for parameter options
     * @return cancelResponse
     * @throws Exception invalid function signature message
     */
    public function cancel($mixed = null) {
        $validParameters = array(
            "(cancel)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("cancel", $args);
    }


    /**
     * Service Call: confirm
     * Parameter options:
     * (confirm) confirm
     * @param mixed,... See function description for parameter options
     * @return confirmResponse
     * @throws Exception invalid function signature message
     */
    public function confirm($mixed = null) {
        $validParameters = array(
            "(confirm)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("confirm", $args);
    }


    /**
     * Service Call: get_finished
     * Parameter options:
     * (get_finished) get_finished
     * @param mixed,... See function description for parameter options
     * @return get_finishedResponse
     * @throws Exception invalid function signature message
     */
    public function get_finished($mixed = null) {
        $validParameters = array(
            "(get_finished)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("get_finished", $args);
    }


    /**
     * Service Call: get_status
     * Parameter options:
     * (get_status) get_status
     * @param mixed,... See function description for parameter options
     * @return get_statusResponse
     * @throws Exception invalid function signature message
     */
    public function get_status($mixed = null) {
        $validParameters = array(
            "(get_status)",
        );
        $args = func_get_args();
            $sargs = array();
                foreach ($mixed as $key=>$val)
                {
                  array_push ($sargs, new SoapParam($val, $key));
                }
            $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("get_status", $sargs);
    }


    /**
     * Service Call: refund
     * Parameter options:
     * (refund) refund
     * @param mixed,... See function description for parameter options
     * @return refundResponse
     * @throws Exception invalid function signature message
     */
    public function refund($mixed = null) {
        $validParameters = array(
            "(refund)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("refund", $args);
    }


    /**
     * Service Call: register
     * Parameter options:
     * (register_online) register_online
     * @param mixed,... See function description for parameter options
     * @return register_onlineResponse
     * @throws Exception invalid function signature message
     */
    public function register($mixed = null) {
        $validParameters = array(
            "(register)",
        );
        $args = func_get_args();
                $sargs = array();
                foreach ($mixed as $key=>$val)
                {
                  array_push ($sargs, new SoapParam($val, $key));
                }
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("register", $sargs);
    }


    /**
     * Service Call: reject
     * Parameter options:
     * (reject) reject
     * @param mixed,... See function description for parameter options
     * @return rejectResponse
     * @throws Exception invalid function signature message
     */
    public function reject($mixed = null) {
        $validParameters = array(
            "(reject)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);

        $sargs = array();
        foreach ($mixed as $key=>$val)
        {
            array_push ($sargs, new SoapParam($val, $key));
        }
        return $this->__soapCall("reject", $sargs);
    }


    /**
     * Service Call: register_online
     * Parameter options:
     * (register_online) register_online
     * @param mixed,... See function description for parameter options
     * @return register_onlineResponse
     * @throws Exception invalid function signature message
     */
    public function register_online($mixed = null) {
        $validParameters = array(
            "(register_online)",
        );
        $args = func_get_args();
                $sargs = array();
                foreach ($mixed as $key=>$val)
                {
                  array_push ($sargs, new SoapParam($val, $key));
                }
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("register_online", $sargs);
    }



}}

?>
