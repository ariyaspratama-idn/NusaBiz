<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\BarcodeService;

class MembershipCardController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    public function show()
    {
        $customer = auth()->user();
        
        // Generate barcode if not exists
        if (!$customer->membership_barcode) {
            $this->barcodeService->assignBarcodeToCustomer($customer);
        }

        $barcodeImage = $this->barcodeService->generateBarcodeImage($customer->membership_barcode);

        return view('customer.membership-card', compact('barcodeImage'));
    }
}
