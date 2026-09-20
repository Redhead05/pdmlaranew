<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
	public function index(Request $request)
	{
		$notifications = $request->user()
			->notifications()
			->latest()
			->paginate(15);

		return view('notifications.index', compact('notifications'));
	}

	public function markAsRead(Request $request, string $notification)
	{
		$item = $request->user()
			->notifications()
			->where('id', $notification)
			->firstOrFail();

		if (! $item->read_at) {
			$item->markAsRead();
		}

		$url = $item->data['action_url'] ?? route('notifications.index');

		if ($request->ajax() || $request->wantsJson()) {
			return response()->json(['ok' => true, 'url' => $url]);
		}

		return redirect($url);
	}
}
