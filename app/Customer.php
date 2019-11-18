<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
//    protected $fillable = ['username'];
//    protected $guarded = ['created_at', 'updated_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'company',
        'street', 'city', 'state',
        'postcode', 'country', 'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function createCustomer(array $user){
        //dd($user);
        Customer::insert($user);
    }
    
    public  function updateCustomer($id,$data){
       // dd($data);
        $customerObj = Customer::find($id['id']);
       $customerObj->update($data);
    }
    
     public function customerAccountDetails()
    {
       
        
        return [
            'name' => $this->name,
            'company' => $this->company,
            'street' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'postcode' => $this->postcode,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
        ]; 
       
    } 
    
    public function shippingAddress()
    {
       
        
        return [
            'name' => $this->name,
            'company' => $this->company,
            'street1' => '814 Mission St.',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94105',
            'country' => 'US',
            'phone' => $this->phone,
            'email' => $this->email,
            'is_complete' => 'True',
        /*   'street_no' => '3',
           'street2' => 'jassran',
           'street3' => 'road',
           'is_residential' => 'True',
           'validation_results' => 'true',  */
           
        ]; 
       
    } 
              
}
