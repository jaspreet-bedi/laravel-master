<?php

namespace App\Services;

use Shippo;
use App\Customer;
use App\Product;
use Shippo_Address;
use Shippo_Shipment;
use Shippo_Transaction;

class Shipping 
{
    protected $fromAddress = [
        'object_purpose' => 'PURCHASE',
        'name' => 'Eric Barnes',
        'company' => 'dotdev inc.',
        'street1' => '814 Mission St.',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '94105',
        'country' => 'US',
        'phone' => '+1 555 341 9393',
        'email' => 'shippotle@goshippo.com',
        'is_complete' => 'true',
        /* 'street_no' => '3',
        'street2' => 'jassran',
        'street3' => 'road',
        'is_residential' => 'True',
        'validation_results' => 'true', */
        
    ];

    public function __construct()
    {
        // Grab this private key from
        // .env and setup the Shippo api
        Shippo::setApiKey(env('SHIPPO_PRIVATE'));
    }

    /**
     * Validate an address through Shippo service
     *
     * @param User $user
     * @return \Shippo_Adress
     */
    public function validateAddress(Customer $customer)
    {
        // Grab the shipping address from the User model
        $toAddress = $customer->shippingAddress();

        // Pass a validate flag to Shippo
        $toAddress['validate'] = true;

        // Verify the address data
        return Shippo_Address::create($toAddress);
    }

    /**
     * Create a Shippo shipping rates
     *
     * @param User $user
     * @param Product $product
     * @return Shippo_Shipment
     */
    public function rates(Customer $customer, Product $product)
    {
        // Grab the shipping address from the User model
        $toAddress = $customer->shippingAddress();
       
        // Pass the PURCHASE flag.
        $toAddress['object_purpose'] = 'PURCHASE';
 //dd("bp2");
     //   die();
        // Get the shipment object
        return Shippo_Shipment::create([
                'object_purpose'=> 'PURCHASE',
                'address_from'=> $this->fromAddress,
                'address_to'=> $toAddress,
                'parcels'=> $product->toArray(),
                'insurance_amount'=> '30',
                'insurance_currency'=> 'USD',
                'async'=> false
        ]);
    }

    /**
     * Create the shipping label transaction
     *
     * @param $rateId -- object_id from rates_list
     * @return Shippo_Transaction
     */
    public function createLabel($rateId)
    {
        //var_dump(dd($rateId));
        return Shippo_Transaction::create([
            'rate' => $rateId,
            'label_file_type' => "PDF",
            'async' => false
        ]);
    }
}