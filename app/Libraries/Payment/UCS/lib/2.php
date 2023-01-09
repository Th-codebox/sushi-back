<?php

        require_once 'orderv2.php';
        $soapClient = new orderv2(null, array('location' => "https://tws.egopay.ru/order/v2/",
                                              'uri'      => "https://ru.crusher.fit",
                                              'login'    => "fit",
                                              'password' => "RIvJ9MTrlY",
                                              'trace'    => 1,
                                              'features' => SOAP_SINGLE_ELEMENT_ARRAYS));

        // Setup the RemoteFunction parameters
        $request = new register_online();
        $order = new OrderID();
        $order->shop_id = "26856";
        $order->number = "1234";
        $cost = new Amount();
        $cost->amount = 1700;
        $cost->currency = "RUB";
        $customer = new CustomerInfo();
        $description = new OrderInfo();
        $description->paytype = "card";

        //$settings['basket_items'] - массив элементов товаров
        //Каждый элемент содержит:
        //Price - цена
        //VAT - процент налога
        //VAT_VALUE - сумма налога
        //QUANTITY - количество
        //NAME - название

        $settings['basket_items'][] = array(
            'PRICE' => 850,
            'NAME' => 'crusher red',
            'VAT' => 0,
            'VAT_VALUE' => 0,
            'QUANTITY' => 2
        );


        $arBasketItems = $settings['basket_items'];
        $arItems = array();
        for ($i = 0; $i < count($arBasketItems); $i++){
            $itemCost = new Amount();
            $itemCost->amount = $arBasketItems[$i]['PRICE'];
            $itemCost->currency = 'RUB';

            //Единственный способ в Soap добавить атрибут type='vat', который был найден на момент создания.
            //TODO: Найти другой способ
            //Налог должен быть int
            $itemTaxes = array(
                new SoapVar("<tax type='vat'><percentage>".substr($arBasketItems[$i]['VAT'],0,2)."</percentage><amount><currency>RUB</currency><amount>".$arBasketItems[$i]['VAT_VALUE']."</amount></amount></tax>", XSD_ANYXML),
            );


            $item = new OrderItem();
            $item->number = "1234";
            $item->amount = $itemCost;
            $item->typename = 'goods';
            $item->quanity = $arBasketItems[$i]['QUANTITY'];
            $item->name = $arBasketItems[$i]['NAME'];
            $item->taxes = new SoapVar($itemTaxes, SOAP_ENC_OBJECT);

            $arItems[] = new SoapVar($item, SOAP_ENC_OBJECT, null, null, 'OrderItem');
        }
        $description->sales = new SoapVar(
                $arItems,
                SOAP_ENC_OBJECT
            );


        $language = new PostEntry();
        $language->name = "Language";
        $language->value = "ru";
        $cardtype = new PostEntry();
        $cardtype->name = "ChoosenCardType";
        $cardtype->value = "VI";


        $returnUrlOk = new PostEntry();
        $returnUrlOk->name = 'ReturnURLOk';
        $returnUrlOk->value = 'http://sushifox.local?pay_ok';

        $returnUrlFault = new PostEntry();
        $returnUrlFault->name = 'ReturnURLFault';
        $returnUrlFault->value = 'http://sushifox.local?pay_ok';



        $request->order = $order;
        $request->cost = $cost;
        $request->customer = $customer;
        $request->description = $description;

        $postdata = new SoapVar([
            new SoapVar($language, SOAP_ENC_OBJECT, null, null, "PostEntry"),
            new SoapVar($cardtype, SOAP_ENC_OBJECT, null, null, "PostEntry"),
            new SoapVar($returnUrlOk, SOAP_ENC_OBJECT, null, null, 'PostEntry'),
            new SoapVar($returnUrlFault, SOAP_ENC_OBJECT, null, null, 'PostEntry')
        ],SOAP_ENC_OBJECT);
        //$postdata = new SoapVar(array($language, $cardtype), SOAP_ENC_OBJECT);

        $request->postdata = $postdata;
        $status = new get_status();
        $order = new OrderID();
        $order->shop_id = "26856";
        $order->number = "1234";
        $status->order = $order;
        // Call RemoteFunction ()
        $error = 0;
        try {
            $info = $soapClient->register_online($request);
            print ($info->redirect_url);
            print ($info->session);
            $info = $soapClient->get_status($status);
            print ($info->status);
        } catch (SoapFault $fault) {
            $error = 1;
            print("Sorry: ".$fault->faultcode."-".$fault->faultstring);
        }
?>
