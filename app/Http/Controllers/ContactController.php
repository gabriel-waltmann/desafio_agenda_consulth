<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\ContactPhone;
use App\Models\Phone;

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
                'phones' => 'required|array',
                'email' => 'required',
                'phones.*.number' => 'required',
                'phones.*.countryCode' => 'required',
            ]);

            $contact = Contact::create($request->all());

            if (!$contact) {
                throw new \Exception('Failed to create contact');
            }

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
            ];

            return response()->json($response, 201);
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

            $contact->delete();

            \DB::commit();

            return response()->json(['message' => 'Contact deleted successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}
