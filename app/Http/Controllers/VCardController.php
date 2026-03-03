<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\VCard;

class VCardController extends Controller
{
    /**
     * Display the client-specific vCard.
     *
     * @param string $client
     * @return \Illuminate\View\View
     */
    public function show($client)
    {
        // Fetch client data based on subdomain
        $clientData = Client::where('subdomain', $client)->firstOrFail();

        // Fetch vCard data for the client
        $vcard = VCard::where('client_id', $clientData->id)->firstOrFail();

        // Render the appropriate Blade template with vCard data
        return view('vcards.' . $vcard->template_name, ['data' => json_decode($vcard->data)]);
    }
}