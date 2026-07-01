<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // Sandbox
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function create(Event $event)
    {
        $categories = \App\Models\Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        // Validasi input
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // Cek stok
        if ($event->stock <= 0) {
            return back()->with(
                'error',
                'Mohon maaf, tiket untuk acara ini sudah habis.'
            );
        }

        // Generate Order ID
        $orderId = 'TRX-' . time() . '-' . strtoupper(Str::random(5));

        // Total pembayaran
        $totalPrice = $event->price + 5000;

        // Simpan transaksi
        $transaction = Transaction::create([
            'event_id'       => $event->id,
            'order_id'       => $orderId,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $totalPrice,
            'status'         => 'pending',
        ]);

        // Parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,
            ],
        ];

        try {
            // Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan token ke database
            $transaction->update([
                'snap_token' => $snapToken,
            ]);

            // Redirect ke halaman payment
            return redirect()->route(
                'checkout.payment',
                $transaction->order_id
            );

        } catch (\Exception $e) {

            // Hapus transaksi jika gagal membuat Snap Token
            $transaction->delete();

            return back()->with(
                'error',
                'Gagal memproses pembayaran: ' . $e->getMessage()
            );
        }
    }

    public function payment($order_id)
    {
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        return view(
            'checkout.payment',
            compact('transaction', 'categories')
        );
    }

    public function success($order_id)
    {
        $categories = \App\Models\Category::all();

        $transaction = Transaction::where(
            'order_id',
            $order_id
        )->firstOrFail();

        try {

            $midtransStatus = \Midtrans\Transaction::status($order_id);

            if (
                in_array(
                    $midtransStatus->transaction_status,
                    ['capture', 'settlement']
                )
            ) {
                $transaction->update([
                    'status' => 'success'
                ]);

                // Kurangi stok event jika belum pernah sukses
                if ($transaction->event) {
                    $transaction->event->decrement('stock');
                }
            }

        } catch (\Exception $e) {

            return redirect()
                ->route('home')
                ->with(
                    'error',
                    'Transaksi tidak ditemukan atau gagal diproses.'
                );
        }

        return view(
            'checkout.success',
            compact('transaction', 'categories')
        );
    }
}