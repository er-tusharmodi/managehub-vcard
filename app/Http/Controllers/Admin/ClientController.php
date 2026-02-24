<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vcard;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $validSorts = ['name', 'email', 'created_at', 'username'];
        $validDirections = ['asc', 'desc'];
        
        if (!in_array($sort, $validSorts)) {
            $sort = 'created_at';
        }
        if (!in_array($direction, $validDirections)) {
            $direction = 'desc';
        }
        
        $totalClients = User::whereHas('vcards')->count();
        $activeSubscriptions = Vcard::where('subscription_status', 'active')->count();
        
        $clients = User::with('vcards')
            ->whereHas('vcards')
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->appends(request()->query());

        return view('admin.clients.index', [
            'clients' => $clients,
            'baseDomain' => config('vcard.base_domain'),
            'totalClients' => $totalClients,
            'activeSubscriptions' => $activeSubscriptions,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }
}
