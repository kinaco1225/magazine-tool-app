<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request, Tool $tool)
    {
        abort_if($tool->company_id !== Auth::user()->company_id, 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        Order::create([
            'company_id' => $tool->company_id,
            'tool_id'    => $tool->id,
            'quantity'   => $validated['quantity'],
            'status'     => 'ordered',
            'ordered_at' => now(),
        ]);

        return back()->with('success', "「{$tool->name}」を {$validated['quantity']} 個発注しました。");
    }

    // 入荷処理（一部のみ届いた場合は発注レコードを分割する）
    public function receive(Request $request, Tool $tool)
    {
        abort_if($tool->company_id !== Auth::user()->company_id, 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $remaining    = $validated['quantity'];
        $totalOrdered = Order::where('tool_id', $tool->id)->where('status', 'ordered')->sum('quantity');

        if ($remaining > $totalOrdered) {
            return back()->with('error', "入荷数が発注中の数量（{$totalOrdered}）を超えています。");
        }

        DB::transaction(function () use ($tool, &$remaining) {
            $orders = Order::where('tool_id', $tool->id)
                ->where('status', 'ordered')
                ->orderBy('ordered_at')
                ->orderBy('id')
                ->get();

            foreach ($orders as $order) {
                if ($remaining <= 0) {
                    break;
                }

                if ($order->quantity <= $remaining) {
                    // この発注を全て受領
                    $remaining -= $order->quantity;
                    $order->update(['status' => 'received', 'received_at' => now()]);
                } else {
                    // この発注を分割：受領分を新規レコードに、残りは元の発注として残す
                    Order::create([
                        'company_id'  => $tool->company_id,
                        'tool_id'     => $tool->id,
                        'quantity'    => $remaining,
                        'status'      => 'received',
                        'ordered_at'  => $order->ordered_at,
                        'received_at' => now(),
                    ]);
                    $order->update(['quantity' => $order->quantity - $remaining]);
                    $remaining = 0;
                }
            }
        });

        $tool->increment('stock_quantity', $validated['quantity']);

        return back()->with('success', "「{$tool->name}」を {$validated['quantity']} 個入荷しました。");
    }
}
