<?php

namespace App\Repositories;

use App\Customer;
use Illuminate\Http\Request;

class CustomerRepository
{
    /**
     * Find a user by email
     *
     * @param $email
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        return Customer::where('email', $email)->first();
    }

    /**
     * Find a user by email or create a new one
     *
     * @param Request $request
     * @return mixed|static
     */
    public function findOrCreate(Request $request)
    {
        if ($customer = $this->findUserByEmail($request->email)) {
            return $customer;
        }

        return Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'country' => $request->country,
            'phone' => $request->phone,
        ]);
    }
}