<?php

namespace App\Services;

use App\Models\User;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;

class BarcodeService
{
    protected BarcodeGeneratorPNG $generator;

    public function __construct()
    {
        $this->generator = new BarcodeGeneratorPNG();
    }

    /**
     * Generate unique membership barcode for new customer
     */
    public function generateMembershipBarcode(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        
        // Get the last barcode for this month
        $lastBarcode = User::where('membership_barcode', 'like', "BM-{$year}{$month}%")
            ->orderBy('membership_barcode', 'desc')
            ->first();

        if ($lastBarcode) {
            // Extract the sequential number and increment
            $lastNumber = (int) substr($lastBarcode->membership_barcode, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "BM-{$year}{$month}{$newNumber}";
    }

    /**
     * Generate barcode image
     */
    public function generateBarcodeImage(string $code, int $widthFactor = 2, int $height = 50): string
    {
        $barcodeData = $this->generator->getBarcode($code, $this->generator::TYPE_CODE_128, $widthFactor, $height);
        return 'data:image/png;base64,' . base64_encode($barcodeData);
    }

    /**
     * Save barcode image to storage
     */
    public function saveBarcodeImage(string $code, string $filename = null): string
    {
        if (!$filename) {
            $filename = $code . '.png';
        }

        $barcodeData = $this->generator->getBarcode($code, $this->generator::TYPE_CODE_128, 2, 50);
        $path = 'barcodes/' . $filename;
        
        Storage::disk('public')->put($path, $barcodeData);
        
        return $path;
    }

    /**
     * Print membership card (generate PDF or image)
     */
    public function printMembershipCard(User $user): string
    {
        if (!$user->membership_barcode) {
            throw new \Exception('User does not have a membership barcode');
        }

        // Generate barcode image
        $barcodeImage = $this->generateBarcodeImage($user->membership_barcode);

        // In a real implementation, you would generate a PDF here
        // For now, we'll return the barcode image path
        return $this->saveBarcodeImage($user->membership_barcode, "member-{$user->id}.png");
    }

    /**
     * Lookup customer by barcode
     */
    public function lookupByBarcode(string $barcode): ?User
    {
        return User::where('membership_barcode', $barcode)
            ->where('role', 'customer')
            ->first();
    }

    /**
     * Validate barcode format
     */
    public function validateBarcode(string $barcode): bool
    {
        // Format: BM-YYYYMMXXX
        return preg_match('/^BM-\d{6}\d{3}$/', $barcode) === 1;
    }

    /**
     * Assign barcode to customer
     */
    public function assignBarcodeToCustomer(User $user): string
    {
        if ($user->role !== 'customer') {
            throw new \Exception('Barcode can only be assigned to customers');
        }

        if ($user->membership_barcode) {
            return $user->membership_barcode;
        }

        $barcode = $this->generateMembershipBarcode();
        $user->update([
            'membership_barcode' => $barcode,
            'barcode_printed_at' => now(),
        ]);

        return $barcode;
    }
}
