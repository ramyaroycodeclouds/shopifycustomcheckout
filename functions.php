<?php
require 'require.php';

function vieworder($orderid = '')
{
    if ($orderid == "" || !$orderid) {
        return;
    }
    $apiurl = CONFIG['sticky']['endpoint'] . "/api/v1/order_view";
    $DataQuery = [
        'order_id' => $orderid,
    ];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($DataQuery),
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode(CONFIG['sticky']['username'] . ":" . CONFIG['sticky']['password'])
        ),
    ));
    $rawresponse = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($rawresponse, true);
    return $response;
}

function placeorder($requestData)
{
    $apiurl = CONFIG['sticky']['endpoint'] . "/api/v1/new_order";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($requestData),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Basic " . base64_encode(CONFIG['sticky']['username'] . ":" . CONFIG['sticky']['password'])
        ),
    ));
    $rawresponse = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($rawresponse, true);
    return $response;
}

function shopify_variantid($product_id)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => '' . CONFIG['shopify']['shopify_endpoint'] . '/admin/api/2023-10/products/' . $product_id . '/variants.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'X-Shopify-Access-Token: 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',
            'Content-Type: application/json'
        ),
    ));
    $response_variants = curl_exec($curl);
    curl_close($curl);
    return $response_variants;
}

function shopify_placeorder($shopify_payload)
{

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => '' . CONFIG['shopify']['shopify_endpoint'] . '/admin/api/2024-01/orders.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($shopify_payload),
        CURLOPT_HTTPHEADER => array(
            'X-Shopify-Access-Token:' . CONFIG['shopify']['X-Shopify-Access-Token'] . '',
            'Content-Type: application/json'
        ),
    ));
    $shopifyresponse = curl_exec($curl);
    curl_close($curl);
    return $shopifyresponse;
}
