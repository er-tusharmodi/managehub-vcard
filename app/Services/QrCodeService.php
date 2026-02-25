<?php

namespace App\Services;

use App\Models\Vcard;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate QR code for a vCard
     *
     * @param Vcard $vcard
     * @return string The storage path of the QR code
     */
    public function generateVcardQr(Vcard $vcard): string
    {
        // Generate the vCard URL with protocol
        $vcardUrl = 'https://' . $vcard->subdomain . '.' . config('vcard.base_domain');
        
        // Generate QR code as PNG
        $qrCode = QrCode::format('png')
            ->size(400)
            ->errorCorrection('H')
            ->margin(2)
            ->generate($vcardUrl);
        
        // Define storage path
        $directory = 'vcards/' . $vcard->subdomain;
        $filename = 'qr_code.png';
        $path = $directory . '/' . $filename;
        
        // Ensure directory exists
        Storage::disk('public')->makeDirectory($directory);
        
        // Save QR code to storage
        Storage::disk('public')->put($path, $qrCode);
        
        // Update vcard record with QR path
        $vcard->update(['qr_code_path' => $path]);
        
        return $path;
    }
    
    /**
     * Get the public URL of the QR code
     *
     * @param Vcard $vcard
     * @return string|null
     */
    public function getQrCodeUrl(Vcard $vcard): ?string
    {
        if (!$vcard->qr_code_path) {
            return null;
        }
        
        return Storage::url($vcard->qr_code_path);
    }
    
    /**
     * Check if QR code exists for a vCard
     *
     * @param Vcard $vcard
     * @return bool
     */
    public function qrCodeExists(Vcard $vcard): bool
    {
        if (!$vcard->qr_code_path) {
            return false;
        }
        
        return Storage::disk('public')->exists($vcard->qr_code_path);
    }
    
    /**
     * Delete QR code for a vCard
     *
     * @param Vcard $vcard
     * @return bool
     */
    public function deleteQrCode(Vcard $vcard): bool
    {
        if (!$vcard->qr_code_path) {
            return false;
        }
        
        $deleted = Storage::disk('public')->delete($vcard->qr_code_path);
        
        if ($deleted) {
            $vcard->update(['qr_code_path' => null]);
        }
        
        return $deleted;
    }
    
    /**
     * Regenerate QR code for a vCard (useful when URL changes)
     *
     * @param Vcard $vcard
     * @return string
     */
    public function regenerateQrCode(Vcard $vcard): string
    {
        // Delete old QR code if exists
        $this->deleteQrCode($vcard);
        
        // Generate new QR code
        return $this->generateVcardQr($vcard);
    }
}
