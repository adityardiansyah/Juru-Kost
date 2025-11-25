<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Bill;
use App\Models\Resident;
use App\Models\Room;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.url');
        $this->apiKey = config('services.whatsapp.key');
    }

    /**
     * Send payment reminder
     */
    public function sendPaymentReminder(Bill $bill): bool
    {
        $resident = $bill->resident;
        
        $message = "🏠 *Pengingat Pembayaran Kost*\n\n";
        $message .= "Halo {$resident->name},\n\n";
        $message .= "Tagihan bulan " . $bill->bill_date->format('F Y') . "\n";
        $message .= "No: {$bill->bill_number}\n";
        $message .= "Jumlah: Rp " . number_format($bill->total_amount, 0, ',', '.') . "\n";
        $message .= "Jatuh Tempo: " . $bill->due_date->format('d F Y') . "\n\n";
        $message .= "Mohon segera melakukan pembayaran. Terima kasih! 🙏";

        return $this->send($resident->phone, $message);
    }

    /**
     * Send contract expiration notice
     */
    public function sendContractExpiration(Resident $resident, int $daysLeft): bool
    {
        $message = "🏠 *Pemberitahuan Kontrak Kost*\n\n";
        $message .= "Halo {$resident->name},\n\n";
        $message .= "Kontrak kost Anda akan berakhir dalam {$daysLeft} hari.\n";
        $message .= "Tanggal berakhir: " . $resident->exit_date->format('d F Y') . "\n\n";
        $message .= "Silakan hubungi kami untuk perpanjangan. Terima kasih! 🙏";

        return $this->send($resident->phone, $message);
    }

    /**
     * Send maintenance notification
     */
    public function sendMaintenanceNotification(string $phone, string $ticketNumber, string $status): bool
    {
        $statusText = [
            'pending' => 'diterima dan sedang diproses',
            'in_progress' => 'sedang dikerjakan',
            'completed' => 'telah selesai',
        ];

        $message = "🔧 *Update Tiket Maintenance*\n\n";
        $message .= "No Tiket: {$ticketNumber}\n";
        $message .= "Status: " . ($statusText[$status] ?? $status) . "\n\n";
        $message .= "Terima kasih atas laporannya! 🙏";

        return $this->send($phone, $message);
    }

    /**
     * Send WhatsApp message via API
     */
    protected function send(string $phone, string $message): bool
    {
        try {
            // Format phone number (remove leading 0, add 62)
            $phone = preg_replace('/^0/', '62', $phone);

            // Example using Fonnte API
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post($this->apiUrl, [
                'target' => $phone,
                'message' => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp send failed: ' . $e->getMessage());
            return false;
        }
    }
}