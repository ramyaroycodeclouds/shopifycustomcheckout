<?php
require ('functions.php');
header('Content-Type: application/json');
 
$campaignId = filter_input(INPUT_POST, 'campaignId', FILTER_SANITIZE_SPECIAL_CHARS) ;
$offer_id = filter_input(INPUT_POST, 'offer_id', FILTER_SANITIZE_SPECIAL_CHARS) ;
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_SPECIAL_CHARS) ;
$firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS) ;
$lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS) ;
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS) ;
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS) ;
$shippingAddress1 = filter_input(INPUT_POST, 'shippingAddress1', FILTER_SANITIZE_SPECIAL_CHARS) ;
$shippingAddress2 = filter_input(INPUT_POST, 'shippingAddress2', FILTER_SANITIZE_SPECIAL_CHARS) ;
$shippingCity = filter_input(INPUT_POST, 'shippingCity', FILTER_SANITIZE_SPECIAL_CHARS) ;
$shippingCountry = filter_input(INPUT_POST, 'shippingCountry', FILTER_SANITIZE_SPECIAL_CHARS) ;
$shippingState = filter_input(INPUT_POST, 'shippingState', FILTER_SANITIZE_SPECIAL_CHARS) ;
$shippingZip = filter_input(INPUT_POST, 'shippingZip', FILTER_SANITIZE_SPECIAL_CHARS) ;
$billingSameAsShipping = filter_input(INPUT_POST, 'billingSameAsShipping', FILTER_SANITIZE_SPECIAL_CHARS) ;
$creditCardType = filter_input(INPUT_POST, 'creditCardType', FILTER_SANITIZE_SPECIAL_CHARS) ;
$creditCardNumber = filter_input(INPUT_POST, 'creditCardNumber', FILTER_SANITIZE_SPECIAL_CHARS) ;
$expmonth = filter_input(INPUT_POST, 'expmonth', FILTER_SANITIZE_SPECIAL_CHARS) ;
$expyear = filter_input(INPUT_POST, 'expyear', FILTER_SANITIZE_SPECIAL_CHARS) ;
$CVV = filter_input(INPUT_POST, 'CVV', FILTER_SANITIZE_SPECIAL_CHARS) ;

if ($billingSameAsShipping == 'yes') {
    $billingAddress1 = $shippingAddress1;
    $billingAddress2 = $shippingAddress2;
    $billingCity = $shippingCity;
    $billingState = $shippingState;
    $billingZip = $shippingZip;
    $billingCountry = $shippingCountry;
}
else
{
    $billingAddress1 = filter_input(INPUT_POST, 'billingAddress1', FILTER_SANITIZE_SPECIAL_CHARS) ;
    $billingAddress2 = filter_input(INPUT_POST, 'billingAddress2', FILTER_SANITIZE_SPECIAL_CHARS) ;
    $billingCity = filter_input(INPUT_POST, 'billingCity', FILTER_SANITIZE_SPECIAL_CHARS) ;
    $billingState = filter_input(INPUT_POST, 'billingState', FILTER_SANITIZE_SPECIAL_CHARS) ;
    $billingZip = filter_input(INPUT_POST, 'billingZip', FILTER_SANITIZE_SPECIAL_CHARS) ;
    $billingCountry = filter_input(INPUT_POST, 'billingCountry', FILTER_SANITIZE_SPECIAL_CHARS) ;
}

/**
 * @Card Validation 
 * 
**/

 $expirationDate =  $expmonth.$expyear;
 $cardDate = DateTime::createFromFormat('my', $expirationDate);

$currentDate = new DateTime('now');
$interval = $currentDate->diff($cardDate);
 

if ( $interval->invert == 1 ) {
    echo json_encode(array("status"=>false,"message"=>array("month_year"=>"Card expire or about to expire. Try another one"),"response"=>(object)array()));
    return false;
    die(); 
}
 
/**
 * @Card Validation ends
 * 
**/

/**
 * @PREPARING DATA TO BE SENT TO CRM
 * 
**/
$payload_data = array(
    'campaignId'=>$campaignId,
    'firstName'=>$firstName,
    'lastName'=>$lastName,
    'currency' => CONFIG['currency'],
    'email'=>$email,
    'phone'=>$phone,
    'shippingAddress1'=>$shippingAddress1,
    'shippingAddress2'=>$shippingAddress2,
    'shippingCity'=>$shippingCity,
    'shippingCountry'=>$shippingCountry,
    'shippingState'=>$shippingState,
    'shippingZip'=>$shippingZip,
    'billingSameAsShipping'=>$billingSameAsShipping,
    'billingAddress1'=>$billingAddress1,
    'billingAddress2'=>$billingAddress2,
    'billingCity'=>$billingCity,
    'billingState'=>$billingState,
    'billingZip'=>$billingZip,
    'billingCountry'=>$billingCountry,
    'creditCardType'=>$creditCardType,
    'creditCardNumber'=>$creditCardNumber,
);

$payload_data["offers"] = [
    array(
        "offer_id" => $offer_id,
        "product_id" =>  $product_id,
        "billing_model_id" => 2,
        "quantity" => 1
    )
];

$payload_data['expirationDate'] = $expirationDate;
$payload_data['CVV'] = $CVV;
$payload_data['tranType'] = CONFIG['tranType'];
$payload_data['ipAddress'] = $_SERVER['REMOTE_ADDR'];
$payload_data['shippingId'] = CONFIG['shipping_id'];

$_GET_upper = array_change_key_case($_REQUEST, CASE_UPPER);
$_GET_lower = array_change_key_case($_REQUEST, CASE_LOWER);

$payload_data['AFID'] = htmlspecialchars(isset($_GET_upper['AFID']) ? $_GET_upper['AFID'] : '');
$payload_data['AFFID'] = htmlspecialchars(isset($_GET_upper['AFFID']) ? $_GET_upper['AFFID'] : '');
$payload_data['SID'] = htmlspecialchars(isset($_GET_upper['SID']) ? $_GET_upper['SID'] : '');
$payload_data['C1'] = htmlspecialchars(isset($_GET_upper['C1']) ? $_GET_upper['C1'] : '');
$payload_data['C2'] = htmlspecialchars(isset($_GET_upper['C2']) ? $_GET_upper['C2'] : '');
$payload_data['C3'] = htmlspecialchars(isset($_GET_upper['C3']) ? $_GET_upper['C3'] : '');
$payload_data['click_id'] = htmlspecialchars(isset($_GET_lower['click_id']) ? $_GET_lower['click_id'] : '');

/**
  * CRM PAYLOAD DATA ENDS
**/

$response = placeorder($payload_data);

if($response['response_code']==100)
{
    $view_order_response =  vieworder($response['order_id']);     
   /**
    * Shopify PAYLOAD DATA
    **/
    $shopify_payload =[
        'order' => [
            'line_items'=>[ 
                [
                 "title"=> $view_order_response['products'][0]['name'],
                 "price"=> $view_order_response['products'][0]['price'],
                 "variant_id"=> CONFIG['product_mapp']['products']['product_details'][0]['shopify_vid'],
                 "quantity"=> $view_order_response['products'][0]['product_qty']
                ],
            ], 
            "customer"=>[
                "first_name"=>$view_order_response["first_name"], 
                "email"=> $view_order_response["email_address"], 
                "last_name"=> $view_order_response["last_name"] 
            ],
            "billing_address"=> [
                "first_name"=> $view_order_response["shipping_first_name"],
                "last_name"=> $view_order_response["shipping_last_name"],
                "address1"=> $view_order_response["shipping_street_address"],
                "address2"=> $view_order_response["shipping_street_address2"],
                "city"=> $view_order_response["shipping_city"],
                "province"=> $view_order_response["shipping_state"],
                "country"=> $view_order_response["shipping_country"],
                "zip"=> $view_order_response["shipping_postcode"]
            ],
            "shipping_address"=> [
                "first_name"=> $view_order_response["shipping_first_name"],
                "last_name"=> $view_order_response["shipping_last_name"],
                "address1"=> $view_order_response["shipping_street_address"],
                "address2"=> $view_order_response["shipping_street_address2"],
                "city"=> $view_order_response["shipping_city"],
                "province"=> $view_order_response["shipping_state"],
                "country"=> $view_order_response["shipping_country"],
                "zip"=> $view_order_response["shipping_postcode"]
            ],
            "email"=> $view_order_response["email_address"],
            "transactions" => [
                [
                "kind"=> CONFIG['tranType'],
                "status"=> "success",
                "kind"=> "authorization",
                "amount"=> $view_order_response['totals_breakdown']['total']
                ]
            ],

            'financial_status'=> 'paid',
            'inventory_behaviour'=> 'decrement_obeying_policy',
            'fulfillment_status'=>null,
            "total_tax"=> 0.00,
            "currency"=> CONFIG['currency'],
            "test"=> false,
            "send_receipt" => false,
            "send_fulfillment_receipt" => false   
        ]
    ];
     
    $shopifyresponse = shopify_placeorder($shopify_payload);
    $shopifyresponse = json_decode($shopifyresponse);

    if(isset($shopifyresponse->order->order_number))
    {
       $data = 'thankyou.php?order_id='.$response['order_id'].'&store_orderno='.$shopifyresponse->order->order_number.'' ;
       echo json_encode(array("status"=>true,"message"=>"success","data"=>array("response"=>array("redirect"=>$data,"order_id"=>$response['order_id'],"store_orderno"=>$shopifyresponse->order->order_number))));
       die();
    }
    else
    {
        echo json_encode(array("status"=>false,"message"=>array("shopify_response"=>$shopifyresponse),"data"=>(object)array()));
        die();
    }
}
else
{
    echo json_encode(array("status"=>false,"message"=>array("crm_error"=>$response),"data"=>(object)array()));
    die();
}