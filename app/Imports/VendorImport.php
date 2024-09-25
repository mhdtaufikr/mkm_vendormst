<?php

namespace App\Imports;

use App\Models\VendorMaster;
use App\Models\VendorChange;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class VendorImport implements ToModel, WithHeadingRow
{
    // Define arrays for descriptions
    private $accountGroupDescriptions = [
        "1" => "MKM Local Vendor",
        "2" => "MKM Overseas Vendor",
        "3" => "MKM General Affairs Vendor",
        "4" => "MKM Employee Vendor",
        "5" => "MKM Trade Vendor",
        "6" => "MKM Non Trade Vendor",
        "7" => "MKM Others / Individual Vendor",
        "8" => "Government Related Vendor",
        "9" => "MKM Related Parties",
        "10" => "MKM Third Parties"
    ];

    private $withholdingTaxDescriptions = [
        "1" => "W/H Tax 23 (2%) Jasa aktuaris;",
        "2" => "W/H Tax 23 (2%) Jasa akuntansi, pembukuan, dan atestasi laporan keuangan;",
        "3" => "W/H Tax 23 (2%) Jasa pengolahan limbah, pembasmian hama;",
        "4" => "W/H Tax 23 (2%) Jasa penyedia tenaga kerja dan atau tenaga ahli (outsourcing services);",
        "5" => "W/H Tax 23 (2%) Jasa perantara dan atau keagenan, pengurusan dokumen;",
        "6" => "W/H Tax 23 (2%) Jasa sehubungan dengan software atau hardware atau sistem komputer, internet termasuk sambungannya;",
        "7" => "W/H Tax 23 (2%) Jasa pemasangan/perawatan mesin, peralatan, listrik, telepon, air, gas, AC, dan atau TV kabel;",
        "8" => "W/H Tax 23 (2%) Jasa sewa/perawatan kendaraan dan atau alat transportasi darat, laut dan udara;",
        "9" => "W/H Tax 23 (2%) Jasa maklon;",
        "10" => "W/H Tax 23 (2%) Jasa kebersihan atau cleaning service, pemeliharaan kolam;",
        "11" => "W/H Tax 23 (2%) Jasa katering atau tata boga;",
        "12" => "W/H Tax 23 (2%) Jasa freight forwarding;",
        "13" => "W/H Tax 23 (2%) Jasa pengepakan, loading dan unloading;",
        "14" => "W/H Tax 23 (2%) Jasa sertifikasi;",
        "15" => "W/H Tax 26 (10%) Sewa Tanah / Bangunan;",
        "16" => "W/H Tax 4 (2%) Jasa Konstruksi dengan KLU Kecil;",
        "17" => "W/H Tax 4 (3%) Jasa Konstruksi dengan KLU Menengah dan Besar;",
        "18" => "W/H Tax 4 (4%) Jasa Konstruksi tidak memiliki KLU;"
    ];

    // Helper function to validate and convert input data to string if it's an integer or float
    private function validateAndConvertToString($data): string
    {
        // If the input is a float, convert it to a string with comma-separated values
        if (is_float($data)) {
            return str_replace('.', ',', strval($data));
        }

        // Convert any type of input to string to ensure consistent handling
        return strval($data);
    }

    public function model(array $row)
    {
        // Validate and convert input data to string if necessary
        $accountGroupData = $this->validateAndConvertToString($row['account_group']);
        $withholdingTaxData = $this->validateAndConvertToString($row['withholding_tax']);

        // Convert account_group numbers to descriptions and join with "|"
        $accountGroups = explode(',', $accountGroupData);
        $accountGroupDescriptions = array_map(function ($group) {
            return $this->accountGroupDescriptions[trim($group)] ?? trim($group);
        }, $accountGroups);
        $accountGroupString = implode(',', $accountGroupDescriptions);

        // Convert withholding_tax numbers to descriptions and join with "|"
        $withholdingTaxGroups = explode(',', $withholdingTaxData);
        $withholdingTaxDescriptions = array_map(function ($tax) {
            return $this->withholdingTaxDescriptions[trim($tax)] ?? trim($tax);
        }, $withholdingTaxGroups);
        $withholdingTaxString = implode('|', $withholdingTaxDescriptions);

        DB::beginTransaction();
        try {
            // Insert into vendor_masters table
            $vendor = VendorMaster::create([
                'vendor_account_number' => $row['vendor_account_number'],
                'company_code' => $row['company_code'],
                'account_group' => $accountGroupString, // Converted descriptions with "|"
                'vendor_name' => $row['vendor_name'],
                'title' => $row['title'],
                'department' => $row['department'],
                'name' => $row['name'],
                'search_term_1' => $row['search_term_1'],
                'search_term_2' => $row['search_term_2'],
                'street' => $row['street'],
                'house_number' => $row['house_number'],
                'postal_code' => $row['postal_code'],
                'city' => $row['city'],
                'country' => $row['country'],
                'region' => $row['region'],
                'po_box' => $row['po_box'],
                'telephone' => $row['telephone'],
                'fax' => $row['fax'],
                'email' => $row['email'],
                'tax_code' => $row['tax_code'],
                'npwp' => $row['npwp'],
                'currency' => $row['currency'],
                'bank_key' => $row['bank_key'],
                'bank_account' => $row['bank_account'],
                'account_holder' => $row['account_holder'],
                'bank_region' => $row['bank_region'],
                'confirm_with' => $row['confirm_with'],
                'confirm_info' => $row['confirm_info'],
                'date' => $row['date'],
                'confirm_by' => $row['confirm_by'],
                'recon_account' => $row['recon_account'],
                'sort_key' => $row['sort_key'],
                'cash_management_group' => $row['cash_management_group'],
                'payment_terms' => $row['payment_terms'],
                'payment_method' => $row['payment_method'],
                'payment_block' => $row['payment_block'],
                'withholding_tax' => $withholdingTaxString, // Converted descriptions with "|"
                'remarks' => $row['remarks'],
            ]);

            // Insert into vendor_changes table with default values
            VendorChange::create([
                'vendor_id' => $vendor->id, // Reference to vendor_masters table
                'change_type' => 'Create', // Default value
                'previous_sap_vendor_number' => null, // Default value
                'remarks' => $row['remarks'], // Use remarks from the row
                'status' => 'Completed', // Default value
                'level' => 8, // Default value
                'created_by' => 1, // Default value
                'approved_by' => null, // Default value
                'approved_at' => null, // Default value
                'created_at' => now(), // Use current timestamp
                'updated_at' => now(), // Use current timestamp
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e; // Optional: Add logging here
        }

        return null; // No need to return a model as we're handling the insert manually
    }
}
