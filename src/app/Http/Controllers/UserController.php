<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Illuminate\Auth\Access\Response $response
     */
    public function search(Request $request): object
    {
        $request->validate([
            'terms' => ['required', 'alpha'],
        ]);

        $query = User::where('last_name', 'like', '%' . $request->terms . '%')
            ->orWhere('first_name', 'like', '%' . $request->terms . '%')
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc');
        
        // should we exclude duplicates?
        if ($request->dupes == 'true') {
            $query->groupBy('first_name');
            $query->groupBy('last_name');
        }

        $matches = $query->get();

        return response()->json($matches, 200);
    }
}
