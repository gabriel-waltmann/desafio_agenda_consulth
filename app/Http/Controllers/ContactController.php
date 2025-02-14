<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\ContactPhone;
use App\Models\Phone;
use App\Models\ContactAddress;
use App\Models\Address;

class ContactController extends Controller {
    public function index() {
        $contacts = Contact::with('phones.phone')->get();

        $response = $contacts->map(function ($contact) {
            return [
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'phones' => $contact->phones->map(function ($contactPhone) {
                    return [
                        'id' => $contactPhone->id,
                        'contact_id' => $contactPhone->contact_id,
                        'phone_id' => $contactPhone->phone_id,
                        'phone' => [
                            'id' => $contactPhone->phone->id,
                            'number' => $contactPhone->phone->number,
                            'countryCode' => $contactPhone->phone->countryCode,
                        ],
                    ];
                })->toArray(),
            ];
        });
    

        return response()->json($response);
    }

    public function store(Request $request) {
        \DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phones' => 'required|array',
                'phones.*.number' => 'required',
                'phones.*.countryCode' => 'required',
                'address' => 'required',
                'address.country' => 'required',
                'address.state' => 'required',
                'address.city' => 'required',
                'address.neighborhood' => 'required',
                'address.address' => 'required',
                'address.zipCode' => 'required',
            ]);

            // store contact
            $contact = Contact::create($request->all());

            if (!$contact) {
                throw new \Exception('Failed to create contact');
            }

            // store phones
            foreach ($request->phones as $phone) {
                $phone = Phone::create($phone);

                if (!$phone) {
                    throw new \Exception('Failed to create phone');
                }

                $contactPhone = ContactPhone::create([
                    'contact_id' => $contact->id,
                    'phone_id' => $phone->id,
                ]);

                if (!$contactPhone) {
                    throw new \Exception('Failed to create contact phone');
                }
            }

            // store address
            $address = Address::create($request->address);
    
            if (!$address) {
                throw new \Exception('Failed to create address');
            }

            $contactAddress = ContactAddress::create([
                'contact_id' => $contact->id,
                'address_id' => $address->id,
            ]);

            if (!$contactAddress) {
                throw new \Exception('Failed to create contact address');
            }

            \DB::commit();

            $response = [
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'phones' => $contact->phones->map(function ($contactPhone) {
                    return [
                        'id' => $contactPhone->id,
                        'contact_id' => $contactPhone->contact_id,
                        'phone_id' => $contactPhone->phone_id,
                        'phone' => [
                            'id' => $contactPhone->phone->id,
                            'number' => $contactPhone->phone->number,
                            'countryCode' => $contactPhone->phone->countryCode,
                        ],
                    ];
                })->toArray(),
                'address' => [
                    'id' => $address->id,
                    'contact_id' => $contactAddress->contact_id,
                    'address_id' => $contactAddress->address_id,
                    'address' => [
                        'id' => $address->id,
                        'country' => $address->country,
                        'city' => $address->city,
                        'neighborhood' => $address->neighborhood,
                        'address' => $address->address,
                        'zipCode' => $address->zipCode,
                    ]
                ]
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id) {
        \DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phones' => 'required|array',
                'phones.*.number' => 'required',
                'phones.*.countryCode' => 'required',
                'address' => 'required',
                'address.id' => 'required',
                'address.country' => 'required',
                'address.state' => 'required',
                'address.city' => 'required',
                'address.neighborhood' => 'required',
                'address.address' => 'required',
                'address.zipCode' => 'required',
            ]);

            $contact = Contact::find($id);

            if (!$contact) {
                throw new \Exception('Contact not found');
            }

            // update contact
            $contact->update($request->all());

            // delete all related contact phones
            $oldContactPhones = ContactPhone::where('contact_id', $contact->id)->get();
            foreach ($oldContactPhones as $contactPhone) {
                $contactPhone->delete();

                $phone = Phone::find($contactPhone->phone_id);
                if ($phone) {
                    $phone->delete();
                }
            }

            // crete new phones
            foreach ($request->phones as $phone) {
                $phone = Phone::create($phone);

                if (!$phone) {
                    throw new \Exception('Failed to create phone');
                }

                $contactPhone = ContactPhone::create([
                    'contact_id' => $contact->id,
                    'phone_id' => $phone->id,
                ]);

                if (!$contactPhone) {
                    throw new \Exception('Failed to create contact phone');
                }
            }

            $contactAddress = ContactAddress::where('contact_id', $contact->id)->first();

            if (!$contactAddress) {
                throw new \Exception('Contact address not found');
            }

            $address = Address::find($contactAddress->address_id);

            if (!$address) {
                throw new \Exception('Address not found');
            }

            // update address
            $address->update($request->address);

            \DB::commit();

            $response = [
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'phones' => $contact->phones->map(function ($contactPhone) {
                    return [
                        'id' => $contactPhone->id,
                        'contact_id' => $contactPhone->contact_id,
                        'phone_id' => $contactPhone->phone_id,
                        'phone' => [
                            'id' => $contactPhone->phone->id,
                            'number' => $contactPhone->phone->number,
                            'countryCode' => $contactPhone->phone->countryCode,
                        ],
                    ];
                })->toArray(),
                'address' => [
                    'id' => $address->id,
                    'contact_id' => $contactAddress->contact_id,
                    'address_id' => $contactAddress->address_id,
                    'address' => [
                        'id' => $address->id,
                        'country' => $address->country,
                        'city' => $address->city,
                        'neighborhood' => $address->neighborhood,
                        'address' => $address->address,
                        'zipCode' => $address->zipCode,
                    ]
                ]
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function destroy($id) {
        \DB::beginTransaction();
        try {
            $contact = Contact::find($id);

            if (!$contact) {
                throw new \Exception('Contact not found');
            }

            // Delete all related contact phones
            $phones = ContactPhone::where('contact_id', $contact->id)->get();
            foreach ($phones as $contactPhone) {
                $contactPhone->delete();

                $phone = Phone::find($contactPhone->phone_id);

                if ($phone) {
                    $phone->delete();
                }
            }     
            
            // Delete all related address
            $contactAddress = ContactAddress::where('contact_id', $contact->id)->first();

            if (!$contactAddress) {
                throw new \Exception('Contact address not found');
            }

            $contactAddress->delete();

            $address = Address::find($contactAddress->address_id);

            if (!$address) {
                throw new \Exception('Address not found');
            }

            $address->delete();

            // Delete contact
            $contact->delete();

            \DB::commit();

            return response()->json(['message' => 'Contact deleted successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id) {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        $response = [
            'id' => $contact->id,
            'name' => $contact->name,
            'email' => $contact->email,
            'phones' => $contact->phones->map(function ($contactPhone) {
                return [
                    'id' => $contactPhone->id,
                    'contact_id' => $contactPhone->contact_id,
                    'phone_id' => $contactPhone->phone_id,
                    'phone' => [
                        'id' => $contactPhone->phone->id,
                        'number' => $contactPhone->phone->number,
                        'countryCode' => $contactPhone->phone->countryCode,
                    ],
                ];
            })->toArray(),
            'address' => [
                'id' => $contact->address->id,
                'contact_id' => $contact->address->contact_id,
                'address_id' => $contact->address->address_id,
                'address' => [
                    'id' => $contact->address->address->id,
                    'country' => $contact->address->address->country,
                    'state' => $contact->address->address->state,
                    'city' => $contact->address->address->city,
                    'neighborhood' => $contact->address->address->neighborhood,
                    'address' => $contact->address->address->address,
                    'zipCode' => $contact->address->address->zipCode,
                ]
            ]
        ];

        return response()->json($response);
    }
}
