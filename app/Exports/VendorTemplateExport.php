<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class VendorTemplateExport implements WithHeadings, WithEvents
{
    public function headings(): array
    {
        return [
            // Vendor Masters columns
            'Vendor Account Number', 'Company Code', 'Account Group', 'Vendor Name', 'Title',
            'Department', 'Name', 'Search Term 1', 'Search Term 2', 'Street',
            'House Number', 'Postal Code', 'City', 'Country', 'Region',
            'PO Box', 'Telephone', 'Fax', 'Email', 'Tax Code',
            'NPWP', 'Currency', 'Bank Key', 'Bank Account', 'Account Holder',
            'Bank Region', 'Confirm With', 'Confirm Info', 'Date', 'Confirm By',
            'Recon Account', 'Sort Key', 'Cash Management Group', 'Payment Terms',
            'Payment Method', 'Payment Block', 'Withholding Tax', // AK column

            'Remarks' // AL column
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Define comments for each header cell with hardcoded values
                $comments = [
                    // Vendor Masters comments with hardcoded examples
                    'C1' => "Enter the code from the predefined list below:\n" .
                            "Example values:\n" .
                            "1 = MKM Local Vendor\n" .
                            "2 = MKM Overseas Vendor\n" .
                            "3 = MKM General Affairs Vendor\n" .
                            "4 = MKM Employee Vendor\n" .
                            "5 = MKM Trade Vendor\n" .
                            "6 = MKM Non Trade Vendor\n" .
                            "7 = MKM Others / Individual Vendor\n" .
                            "8 = Government Related Vendor\n" .
                            "9 = MKM Related Parties\n" .
                            "10 = MKM Third Parties\n" .
                            "\nList values are:\n" ,


                    // Withholding Tax comments with hardcoded examples
                    'AK1' => "Enter the code from the predefined list below:\n" .
                             "Example values:\n" .
                             "1 = W/H Tax 23 (2%) Jasa aktuaris;\n" .
                             "2 = W/H Tax 23 (2%) Jasa akuntansi, pembukuan, dan atestasi laporan keuangan;\n" .
                             "3 = W/H Tax 23 (2%) Jasa pengolahan limbah, pembasmian hama;\n" .
                             "4 = W/H Tax 23 (2%) Jasa penyedia tenaga kerja dan atau tenaga ahli (outsourcing services);\n" .
                             "5 = W/H Tax 23 (2%) Jasa perantara dan atau keagenan, pengurusan dokumen;\n" .
                             "6 = W/H Tax 23 (2%) Jasa sehubungan dengan software atau hardware atau sistem komputer, internet termasuk sambungannya;\n" .
                             "7 = W/H Tax 23 (2%) Jasa pemasangan/perawatan mesin, peralatan, listrik, telepon, air, gas, AC, dan atau TV kabel;\n" .
                             "8 = W/H Tax 23 (2%) Jasa sewa/perawatan kendaraan dan atau alat transportasi darat, laut dan udara;\n" .
                             "9 = W/H Tax 23 (2%) Jasa maklon;\n" .
                             "10 = W/H Tax 23 (2%) Jasa kebersihan atau cleaning service, pemeliharaan kolam;\n" .
                             "11 = W/H Tax 23 (2%) Jasa katering atau tata boga;\n" .
                             "12 = W/H Tax 23 (2%) Jasa freight forwarding;\n" .
                             "13 = W/H Tax 23 (2%) Jasa pengepakan, loading dan unloading;\n" .
                             "14 = W/H Tax 23 (2%) Jasa sertifikasi;\n" .
                             "15 = W/H Tax 26 (10%) Sewa Tanah / Bangunan;\n" .
                             "16 = W/H Tax 4 (2%) Jasa Konstruksi dengan KLU Kecil;\n" .
                             "17 = W/H Tax 4 (3%) Jasa Konstruksi dengan KLU Menengah dan Besar;\n" .
                             "18 = W/H Tax 4 (4%) Jasa Konstruksi tidak memiliki KLU;\n"
                ];

                // Apply comments to the respective cells with larger size and text formatting
                foreach ($comments as $cell => $comment) {
                    $sheetComment = $sheet->getComment($cell);
                    $sheetComment->getText()->createTextRun($comment)->getFont()->setSize(12); // Set font size to 14
                    $sheetComment->setWidth('600px'); // Set comment box width
                    $sheetComment->setHeight('600px'); // Set comment box height
                    $sheetComment->setMarginLeft('10px'); // Optional: Set left margin for better visibility
                    $sheetComment->setMarginTop('10px'); // Optional: Set top margin for better visibility
                }
            }
        ];
    }
}
