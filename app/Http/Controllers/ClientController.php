<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ClientController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::orderBy('expiry_date', 'asc')->get();

        $expired = $clients->filter(fn($c) => $c->isExpired())->count();
        $urgent = $clients->filter(fn($c) => $c->isUrgent())->count();
        $safe = $clients->filter(fn($c) => $c->isSafe())->count();
        $total = $clients->count();

        return view('clients.index', compact('clients', 'expired', 'urgent', 'safe', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'required|url',
            'email' => 'required|email',
            'expiry_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Klien berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'required|url',
            'email' => 'required|email',
            'expiry_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Data klien berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Klien berhasil dihapus!');
    }

    /**
     * Save FCM token for push notifications
     */
    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'client_id' => 'nullable|exists:clients,id'
        ]);

        if ($request->client_id) {
            $client = Client::find($request->client_id);
            if ($client) {
                $client->update(['fcm_token' => $request->token]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Send test notification
     */
    public function sendTestNotification(Client $client)
    {
        if (!$client->fcm_token) {
            return back()->with('error', 'Client tidak memiliki FCM token');
        }

        $this->firebase->sendNotification(
            $client->fcm_token,
            '🧪 Notifikasi Test',
            'Ini adalah notifikasi test dari sistem pengingat hosting',
            ['client_id' => $client->id, 'test' => 'true']
        );

        return back()->with('success', 'Notifikasi test berhasil dikirim!');
    }
}
