<?php
// File: app/core/WhatsAppService.php
// Service untuk integrasi WhatsApp notifications menggunakan Click To Chat

class WhatsAppService {

    public function __construct() {
        // Tidak ada konfigurasi API yang diperlukan untuk Click To Chat
    }
    
    /**
     * Mengirim notifikasi WhatsApp untuk reminder kunjungan
     */
    public function sendVisitReminder($phone_number, $patient_name, $visit_date, $clinic_name = 'Klinik PMB') {
        // Format nomor telepon (hapus karakter non-digit dan tambahkan kode negara jika perlu)
        $formatted_phone = $this->formatPhoneNumber($phone_number);
        
        // Template pesan reminder
        $message = "🏥 *Pengingat Kunjungan - {$clinic_name}*\n\n";
        $message .= "Halo {$patient_name},\n\n";
        $message .= "Kami ingin mengingatkan Anda tentang jadwal kunjungan:\n";
        $message .= "📅 Tanggal: " . date('d F Y', strtotime($visit_date)) . "\n";
        $message .= "🕐 Waktu: Sesuai jadwal yang telah ditentukan\n\n";
        $message .= "Mohon hadir tepat waktu. Jika ada perubahan jadwal, silakan hubungi kami.\n\n";
        $message .= "Terima kasih 🙏";
        
        return $this->sendMessage($formatted_phone, $message);
    }
    
    /**
     * Mengirim notifikasi WhatsApp untuk reminder imunisasi
     */
    public function sendImmunizationReminder($phone_number, $patient_name, $baby_name, $immunization_type, $due_date, $clinic_name = 'Klinik PMB') {
        $formatted_phone = $this->formatPhoneNumber($phone_number);
        
        $message = "💉 *Pengingat Imunisasi - {$clinic_name}*\n\n";
        $message .= "Halo {$patient_name},\n\n";
        $message .= "Saatnya imunisasi untuk si kecil:\n";
        $message .= "👶 Nama Bayi: {$baby_name}\n";
        $message .= "💉 Jenis Imunisasi: {$immunization_type}\n";
        $message .= "📅 Jadwal: " . date('d F Y', strtotime($due_date)) . "\n\n";
        $message .= "Jangan lupa membawa buku KIA dan kartu imunisasi.\n\n";
        $message .= "Terima kasih 🙏";
        
        return $this->sendMessage($formatted_phone, $message);
    }
    
    /**
     * Fungsi umum untuk membuat URL Click To Chat WhatsApp
     */
    private function sendMessage($phone_number, $message) {
        // Encode pesan untuk URL
        $encoded_message = urlencode($message);

        // Buat URL whatsapp:// untuk membuka aplikasi langsung
        $url = "whatsapp://send?phone={$phone_number}&text={$encoded_message}";

        // Fallback ke web jika aplikasi tidak tersedia
        $web_url = "https://wa.me/{$phone_number}?text={$encoded_message}";

        return [
            'success' => true,
            'url' => $url,
            'web_url' => $web_url,
            'message' => 'Membuka WhatsApp...'
        ];
    }
    
    /**
     * Format nomor telepon ke format internasional
     */
    private function formatPhoneNumber($phone) {
        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Jika dimulai dengan 0, ganti dengan 62 (kode negara Indonesia)
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Jika belum ada kode negara, tambahkan 62
        if (substr($phone, 0, 2) != '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Test koneksi ke WhatsApp Click To Chat
     */
    public function testConnection() {
        return [
            'success' => true,
            'message' => 'WhatsApp Click To Chat siap digunakan.'
        ];
    }
    

}
?>
