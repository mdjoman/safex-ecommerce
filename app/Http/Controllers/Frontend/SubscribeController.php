<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = Subscriber::where('email', $request->email)->first();

        if ($subscriber) {
            if ($subscriber->is_subscribed) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already subscribed!'
                ]);
            }
            $subscriber->subscribe();
        } else {
            $subscriber = Subscriber::create([
                'email' => $request->email,
                'is_subscribed' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription successful!'
        ]);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = Subscriber::where('email', $request->email)->first();

        if (!$subscriber) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriber not found.'
            ]);
        }

        $subscriber->unsubscribe();

        return response()->json([
            'success' => true,
            'message' => 'Unsubscribed successfully!'
        ]);
    }
}
