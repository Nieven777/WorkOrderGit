<?php

namespace App\Http\Controllers;

use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Http\Request;

class WordExportController extends Controller
{
    public function exportToWord(Request $request)
    {
        // Assuming you're sending the order data from the Vue component
        $order = $request->input('order');

        // Path to the template DOCX file
        $templatePath = storage_path('app/public/templates/work_order_template.docx');

        // Load the template using TemplateProcessor
        $templateProcessor = new TemplateProcessor($templatePath);

        // Replace placeholders in the DOCX template with actual data
        $templateProcessor->setValue('ticket_number', $order['ticket_number']);
        $templateProcessor->setValue('requested_by', $order['requested_by']);
        $templateProcessor->setValue('requisitioner_type', $order['requisitioner_type']);
        $templateProcessor->setValue('college', $order['college']);
        $templateProcessor->setValue('department', $order['department']);
        $templateProcessor->setValue('concern', $order['concern']);
        $templateProcessor->setValue('description', $order['description']);
        $templateProcessor->setValue('date_requested', $order['date_requested']);
        $templateProcessor->setValue('status', $order['status']);
        
        if ($order['status'] === 'Completed') {
            $templateProcessor->setValue('category', $order['category']);
            $templateProcessor->setValue('completed_by', $order['completed_by']);
        }
        
        $templateProcessor->setValue('completed_description', $order['completed_description']);

        // Save the modified Word document
        $fileName = 'work_order_' . $order['ticket_number'] . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        // Return the file for download
        return response()->download($filePath);
    }
}
