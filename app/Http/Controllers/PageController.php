<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Basic hardcoded credentials (replace with DB/auth system in production)
        $users = [
            'admin'   => 'secret123',
            'student' => 'password',
        ];

        if (isset($users[$username]) && $users[$username] === $password) {
            session(['username' => $username]);
            return redirect()->route('dashboard', ['username' => $username]);
        }

        return back()->withInput()->withErrors(['login' => 'Username atau password salah.']);
    }

    public function logout()
    {
        session()->forget(['isLoggedIn', 'username']);
        return redirect()->route('login');
    }

    public function dashboard(Request $request)
    {
        $username = $request->query('username', 'guest');
        return view('dashboard', compact('username'));
    }

    public function pengelolaan()
    {
        // Base items - used to seed the session on first visit
        $baseItems = [
            ['Code' => 101, 'Booker(s) Name' => 'Produk A', 'Room Type' => 'Meeting Room', 'status' => 'Available'],
            ['Code' => 102, 'Booker(s) Name' => 'Produk B', 'Room Type' => 'Meeting Room', 'status' => 'Booked'],
            ['Code' => 201, 'Booker(s) Name' => 'Produk C', 'Room Type' => 'Classroom', 'status' => 'Booked'],
            ['Code' => 301, 'Booker(s) Name' => 'Produk D', 'Room Type' => 'Auditorium', 'status' => 'Available'],
            ['Code' => 202, 'Booker(s) Name' => 'Produk E', 'Room Type' => 'Classroom', 'status' => 'Available'],
        ];

        return view('pengelolaan', compact('baseItems'));

        // If session hasn't been seeded yet, seed it with base items so the page "starts with" that array
        $sessionRaw = session('pengelolaan_items', null);

        // If the session key is missing, empty or contains an unexpected type (e.g. string), reset it
        if (!is_array($sessionRaw) || empty($sessionRaw)) {
            // Log unexpected session content for debugging
            if ($sessionRaw !== null && !is_array($sessionRaw)) {
                \Illuminate\Support\Facades\Log::warning('pengelolaan_items session value has unexpected type; reseeding base items.', ['type' => gettype($sessionRaw), 'value' => $sessionRaw]);
            }
            session(['pengelolaan_items' => $baseItems]);
            $sessionRaw = $baseItems;
        }

        // Use the verified session-stored items as the canonical source of data
        $items = $sessionRaw;

        // Apply overrides (if any) stored as ['Code' => item]
        $overrides = session('pengelolaan_overrides', []);
        if (!empty($overrides)) {
            foreach ($items as &$it) {
                if (isset($overrides[$it['Code']])) {
                    $it = $overrides[$it['Code']];
                }
            }
            unset($it);
        }

        // Remove deleted codes
        $deleted = session('pengelolaan_deleted', []);
        if (!empty($deleted)) {
            $items = array_values(array_filter($items, function ($it) use ($deleted) {
                return !in_array((int)$it['Code'], $deleted, true);
            }));
        }

        // optional sorting by Code via ?sort=asc or ?sort=desc
        $sort = request()->query('sort');
        if ($sort === 'asc' || $sort === 'desc') {
            usort($items, function ($a, $b) use ($sort) {
                $ca = (int)$a['Code'];
                $cb = (int)$b['Code'];
                if ($ca === $cb) return 0;
                if ($sort === 'asc') return $ca < $cb ? -1 : 1;
                return $ca > $cb ? -1 : 1;
            });
        }

        $username = session('username');

        // Debug output for items array
        \Illuminate\Support\Facades\Log::debug('Items being passed to view:', ['items' => $items]);

        return view('pengelolaan', compact('items', 'username'));
    }

    /**
     * Handle adding a new item to the pengelolaan list (stored in session).
     */
    public function addItem(Request $request)
    {
        // Get all existing codes to check uniqueness
        $baseItems = [
            ['Code' => 101, 'Booker(s) Name' => 'Produk A', 'Room Type' => 'Meeting Room', 'status' => 'Available'],
            ['Code' => 102, 'Booker(s) Name' => 'Produk B', 'Room Type' => 'Meeting Room', 'status' => 'Booked'],
            ['Code' => 201, 'Booker(s) Name' => 'Produk C', 'Room Type' => 'Classroom', 'status' => 'Booked'],
            ['Code' => 301, 'Booker(s) Name' => 'Produk D', 'Room Type' => 'Auditorium', 'status' => 'Available'],
            ['Code' => 202, 'Booker(s) Name' => 'Produk E', 'Room Type' => 'Classroom', 'status' => 'Available'],
        ];
        $sessionItems = session('pengelolaan_items', []);
        $overrides = session('pengelolaan_overrides', []);

        // Collect all existing codes
        $existingCodes = array_merge(
            array_column($baseItems, 'Code'),
            array_column($sessionItems, 'Code'),
            array_keys($overrides)
        );

        $validated = $request->validate([
            'Code' => [
                'required',
                'numeric',
                function($attribute, $value, $fail) use ($existingCodes) {
                    if (in_array((int)$value, $existingCodes)) {
                        $fail('The Code has already been taken.');
                    }
                },
            ],
            'Booker(s) Name' => 'required|string|max:255',
            'Room Type' => 'required|string|max:255',
            'status' => 'required|in:Available,Booked',
        ]);

        // Create new item with exact keys matching the display structure
        $newItem = [
            'Code' => (int) $validated['Code'],
            'Booker(s) Name' => $validated['Booker(s) Name'],
            'Room Type' => $validated['Room Type'],
            'status' => $validated['status']
        ];

        $baseItems = session('baseItems', []);
        $baseItems[] = $newItem; // menambahkan ke array
        session(['baseItems' => $baseItems]);

        return redirect()->route('pengelolaan')->with('success', 'Item successfully added.');
    }

    /**
     * Show edit form for a specific item.
     * Index refers to the position in the merged items array (0-based).
     */
    public function editItem($code)
    {
        // build items list and find by Code
        $baseItems = [
            ['Code' => 101, 'Booker(s) Name' => 'Produk A', 'Room Type' => 'Meeting Room', 'status' => 'Available'],
            ['Code' => 102, 'Booker(s) Name' => 'Produk B', 'Room Type' => 'Meeting Room', 'status' => 'Booked'],
            ['Code' => 201, 'Booker(s) Name' => 'Produk C', 'Room Type' => 'Classroom', 'status' => 'Booked'],
            ['Code' => 301, 'Booker(s) Name' => 'Produk D', 'Room Type' => 'Auditorium', 'status' => 'Available'],
            ['Code' => 202, 'Booker(s) Name' => 'Produk E', 'Room Type' => 'Classroom', 'status' => 'Available'],
        ];

        $sessionItems = session('pengelolaan_items', []);
        $items = array_merge($sessionItems, $baseItems);

        // apply overrides if any
        $overrides = session('pengelolaan_overrides', []);
        if (!empty($overrides)) {
            foreach ($items as &$it) {
                if (isset($overrides[$it['Code']])) {
                    $it = $overrides[$it['Code']];
                }
            }
            unset($it);
        }

        // find item by Code
        $found = null;
        foreach ($items as $idx => $it) {
            if ((int)$it['Code'] === (int)$code) {
                $found = $it;
                break;
            }
        }

        if ($found === null) {
            return redirect()->route('pengelolaan')->withErrors(['notfound' => 'Item tidak ditemukan.']);
        }

        return view('pengelolaan_edit', ['item' => $found, 'code' => (int)$code]);
    }

    /**
     * Update a specific item at the given index.
     */
    public function updateItem(Request $request, $code)
    {
        $validated = $request->validate([
            'Code' => 'required|numeric',
            'Booker(s) Name' => 'required|string|max:255',
            'Room Type' => 'required|string|max:255',
            'status' => 'required|in:Available,Booked',
        ]);

        // Build base + session items list to check existence
        $baseItems = [
            ['Code' => 101, 'Booker(s) Name' => 'Produk A', 'Room Type' => 'Meeting Room', 'status' => 'Available'],
            ['Code' => 102, 'Booker(s) Name' => 'Produk B', 'Room Type' => 'Meeting Room', 'status' => 'Booked'],
            ['Code' => 201, 'Booker(s) Name' => 'Produk C', 'Room Type' => 'Classroom', 'status' => 'Booked'],
            ['Code' => 301, 'Booker(s) Name' => 'Produk D', 'Room Type' => 'Auditorium', 'status' => 'Available'],
            ['Code' => 202, 'Booker(s) Name' => 'Produk E', 'Room Type' => 'Classroom', 'status' => 'Available'],
        ];

        $sessionItems = session('pengelolaan_items', []);
        $items = array_merge($sessionItems, $baseItems);

        // find the item by Code
        $exists = false;
        foreach ($items as $it) {
            if ((int)$it['Code'] === (int)$code) {
                $exists = true;
                break;
            }
        }

        if (! $exists) {
            return redirect()->route('pengelolaan')->withErrors(['notfound' => 'Item tidak ditemukan.']);
        }

        // Prepare override for specific Code and save into 'pengelolaan_overrides' session key
        $overrides = session('pengelolaan_overrides', []);
        $overrides[(int)$code] = [
            // Keep the original code (route param) immutable
            'Code' => (int) $code,
            'Booker(s) Name' => $validated['Booker(s) Name'],
            'Room Type' => $validated['Room Type'],
            'status' => $validated['status'],
        ];
        session(['pengelolaan_overrides' => $overrides]);

        return redirect()->route('pengelolaan')->with('success', 'Item berhasil diupdate.');
    }

    /**
     * Delete an item by Code. For session-added items, remove them from pengelolaan_items.
     * For base items, add their Code to pengelolaan_deleted so they are hidden.
     */
    public function deleteItem(Request $request, $code)
    {
        $code = (int) $code;

        // Remove from session-added items
        $sessionItems = session('pengelolaan_items', []);
        $newSessionItems = array_values(array_filter($sessionItems, function ($it) use ($code) {
            return ((int) $it['Code']) !== $code;
        }));
        session(['pengelolaan_items' => $newSessionItems]);

        // Remove any overrides for this code
        $overrides = session('pengelolaan_overrides', []);
        if (isset($overrides[$code])) {
            unset($overrides[$code]);
            session(['pengelolaan_overrides' => $overrides]);
        }

        // If the code is not part of session-added items, treat it as a base item and mark deleted
        // (we store deleted codes so pengelolaan() can filter them out)
        $deleted = session('pengelolaan_deleted', []);
        if (!in_array($code, $deleted, true)) {
            $deleted[] = $code;
            session(['pengelolaan_deleted' => $deleted]);
        }

        return redirect()->route('pengelolaan')->with('success', 'Item berhasil dihapus.');
    }

    public function profile(Request $request)
    {
        $username = $request->query('username', 'Tamu');
        $profile = [
            'username' => $username,
            'nama_lengkap' => 'Nasrulloh Tri Ramadhani ',
            'email' => 'nasrullohtriramadhani@gmail.com',
            'role' => 'Administrator',
        ];
        return view('profile', compact('profile'));
    }
}
