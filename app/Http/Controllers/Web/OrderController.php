<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display order form
     */
    public function index(Request $request)
    {
        // Get package slug from query parameter, default to lifetime
        $packageSlug = $request->query('package', 'lifetime-access');

        // Get the package
        $package = Package::where('slug', $packageSlug)
            ->where('is_active', true)
            ->first();

        // If package not found or not available, redirect to homepage
        if (!$package || !$package->isAvailable()) {
            return redirect('/')
                ->with('error', 'Paket yang Anda pilih tidak tersedia saat ini.');
        }

        // Get all active packages for display
        $packages = Package::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('order', compact('package', 'packages'));
    }

    /**
     * Process order submission
     */
    public function store(Request $request)
    {
        // Validate reCAPTCHA first
        $recaptchaResponse = $request->input('g-recaptcha-response');

        if (!$this->verifyRecaptcha($recaptchaResponse)) {
            return back()
                ->withInput()
                ->withErrors(['recaptcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        // Validate form data
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'payment_method' => 'required|in:bank_transfer,gopay,ovo,dana,credit_card,qris',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        // Get package and verify availability
        $package = Package::findOrFail($request->package_id);

        if (!$package->isAvailable()) {
            return back()
                ->withInput()
                ->withErrors(['package' => 'Maaf, paket ini sudah tidak tersedia.']);
        }

        // Create order
        $order = Order::create([
            'package_id' => $package->id,
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'company_name' => $request->company_name,
            'package_price' => $package->price,
            'discount_amount' => ($package->original_price ?? $package->price) - $package->price,
            'total_amount' => $package->price,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'notes' => $request->notes,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send confirmation email
        try {
            $this->sendOrderConfirmationEmail($order);
        } catch (\Exception $e) {
            // Log error but don't fail the order
            \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }

        // Redirect to success page
        return redirect()->route('order.success', ['order' => $order->order_number])
            ->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    /**
     * Display order success page
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return view('order-success', compact('order'));
    }

    /**
     * Verify reCAPTCHA response
     */
    private function verifyRecaptcha($response): bool
    {
        $secretKey = config('services.recaptcha.secret_key');

        // Skip verification in local development if key not set
        if (empty($secretKey) && app()->environment('local')) {
            return true;
        }

        if (empty($response)) {
            return false;
        }

        try {
            $verifyResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $response,
                'remoteip' => request()->ip(),
            ]);

            $responseData = $verifyResponse->json();

            return isset($responseData['success']) && $responseData['success'] === true;
        } catch (\Exception $e) {
            \Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send order confirmation email
     */
    private function sendOrderConfirmationEmail(Order $order): void
    {
        $data = [
            'order' => $order,
            'package' => $order->package,
        ];

        Mail::send('emails.order-confirmation', $data, function ($message) use ($order) {
            $message->to($order->customer_email, $order->customer_name)
                ->subject('Konfirmasi Pesanan - ' . $order->order_number);
        });
    }
}
