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
                             "1 = W/H Tax 23 (2%) Jasa / Sewa selain Tanah atau Bangunan;\n" .
                             "2 = W/H Tax 26 (10%) Jasa/Royalty/Deviden;\n" .
                             "3 = W/H Tax 4-2 (2,65%) Jasa Konstruksi;\n" .
                             "4 = W/H Tax 4-2 (10%) Sewa Tanah / Bangunan;\n" .
                             "5 = W/H Tax 21 (5%) Jasa Notaris;\n" .
                             "6 = Prepaid Tax 23 (2%);\n" .
                             "7 = Final Tax (10%);\n"
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
