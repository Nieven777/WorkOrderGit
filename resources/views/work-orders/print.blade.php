<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Order #{{ $workOrder->ticket_number }}</title>
    <style>
        /* General styling */
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            font-size: 12px;
        }
        
        /* .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 10px;
        }
         */
        /* Header styles */
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .logo {
            width: 100px;
            margin-right: 10px;
        }
        
        .logo img {
            width: 100%;
            border-radius: 50%;
        }
        
        .header-text {
            flex-grow: 1;
            text-align: left;
        }
        
        .header-text h4, .header-text h3 {
            margin: 5px 0;
        }
        
        .divider {
            height: 1px;
            width: 100%;
            background-color: #6c757d;
            margin-bottom: 5px;
        }
        
        .dept-header {
            text-align: left;
            margin: 10px 0;
        }
        
        .form-title {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
        }
        
        /* Form fields and tables */
        .form-group {
            margin-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        td, th {
            border: 1px solid black;
            padding: 6px;
        }
        
        .dotted-line {
            border-bottom: 1px dotted black;
            display: inline-block;
            width: 15%;
        }
        
        /* Form sections */
        .checkboxes {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .checkbox-item {
            margin-right: 15px;
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        input[type="checkbox"] {
            margin-right: 5px;
        }
        
        textarea {
            width: 100%;
            height: 100px;
            border: 1px solid black;
            padding: 5px;
            box-sizing: border-box;
        }
        
        /* Signature sections */
        .signature-block {
            margin-top: 5px;
            margin-bottom: 15px;
        }
        
        .signature-line {
            border-top: 1px solid black;
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            width: 200px;
        }
        
        .signatures {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        
        .signature-column {
            width: 45%;
        }
        
        /* DTO section */
        .dto-section {
            border: 1px solid black;
            padding: 10px;
            margin-top: 15px;
        }
        
        .category-types {
            display: flex;
            flex-wrap: wrap;
        }
        
        .category-item {
            width: 50%;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        
        /* Acceptance and completion section */
        .acceptance-section {
            display: flex;
            margin-top: 20px;
        }
        
        .acceptance-column {
            width: 50%;
            text-align: center;
        }
        
        /* Footer */
        .footer {
            font-size: 10px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        
        /* Print specific styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .container {
                border: none;
                width: 100%;
                max-width: none;
            }
            
            @page {
                margin: 0.5cm;
                size: 8.5in 14in portrait;
            }
            
            /* Hide elements with these classes when printing */
            .no-print {
                display: none !important;
            }
            .header {
            display: flex !important;
            page-break-inside: avoid;
        }
        .logo {
            flex-shrink: 0 !important;
        }

        }
    </style>
    <script>
        // Auto-trigger print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
                // Close window after printing (works in most browsers)
                setTimeout(function() {
                    window.close();
                }, 500);
            }, 200);
        };
    </script>
</head>
<body>
    <div class="container" id="printTable">
<!-- Header with logo - Print-optimized version -->
<div style="display: table; width: 100%; page-break-inside: avoid; padding: top 0;">
    <div style="display: table-cell; vertical-align: middle; width: 100px;">
        <img src="assets/img/logo_cmu.png" alt="CMU Logo" style="height: 100px; width: auto;">
    </div>
    <div style="display: table-cell; vertical-align: middle; padding-left: 20px;">
        <h4 style="color:rgb(0, 0, 0); font-weight: normal; margin: 0;">Republic of the Philippines</h4>
        <h3 style="font-weight: bold; margin: 0 0 5px 0;">CENTRAL MINDANAO UNIVERSITY</h3>
        <h4 style="color:rgb(0, 0, 0); font-weight: normal; margin: 0;">Musuan, Maramag, Bukidnon</h4>
    </div>
</div>

        
        <div class="divider"></div>
        
        <div class="dept-header">
            <h3 style="font-weight: bold;">OFFICE OF DIGITAL TRANSFORMATION</h3>
        </div>
        
        <div class="form-title">
            <h3>WORK ORDER REQUEST FORM</h3>
            <h4 style="color:rgb(0, 0, 0); font-weight: normal;">(Services Only)</h4>
        </div>
        
        <!-- Ticket and Date section -->
        <div class="form-group">
            <span>Ticket No. <span class="dotted-line">{{ $workOrder->ticket_number }}</span></span>
            <span style="text-align: right;">Date: <span class="dotted-line">{{ $workOrder->date_requested }}</span></span>
        </div>
        
<!-- Requester Information Table -->
<table border="1" style="border-collapse: collapse;">
    <tr>
        <td width="25%"><strong>Requested by</strong></td>
        <td width="100%">{{ $workOrder->requested_by }}</td>
    </tr>
    <tr>
        <td width="15%"><strong>College/Unit</strong></td>
        <td width="15%">{{ $workOrder->college }}</td>

        <td width="15%"><strong>Department</strong></td>
        <td width="35%">{{ $workOrder->department }}</td>

    </tr>

</table>
        
        <!-- Work Order Details Table -->
        <table>
            <tr>
                <td colspan="2"><strong>Work Order Details</strong></td>
            </tr>
            <tr>
    <td width="50%">
        <!-- Horizontal checkbox group for Requester Type -->
        <table cellspacing="0" cellpadding="0" style="border: none;">
            <tr>
                <!-- Student -->
                <td style="padding-right: 20px; white-space: nowrap;">
                    <input type="checkbox" id="STUDENT" name="requestType" value="STUDENT" {{ $workOrder->requisitioner_type === 'STUDENT' ? 'checked' : '' }} disabled>
                    <label for="STUDENT">Student</label>
                </td>
                
                <!-- Faculty -->
                <td style="padding-right: 20px; white-space: nowrap;">
                    <input type="checkbox" id="FACULTY" name="requestType" value="FACULTY" {{ $workOrder->requisitioner_type === 'FACULTY' ? 'checked' : '' }} disabled>
                    <label for="FACULTY">Faculty</label>
                </td>
                
                <!-- Staff -->
                <td style="padding-right: 20px; white-space: nowrap;">
                    <input type="checkbox" id="STAFF" name="requestType" value="STAFF" {{ $workOrder->requisitioner_type === 'STAFF' ? 'checked' : '' }} disabled>
                    <label for="STAFF">Staff</label>
                </td>
                
                <!-- Other -->
                <td style="white-space: nowrap;">
                    <input type="checkbox" id="otherType" name="requestType" value="other" {{ !in_array($workOrder->requisitioner_type, ['FACULTY', 'STAFF', 'STUDENT']) ? 'checked' : '' }} disabled>
                    <label for="otherType">Other, specify:</label>
                    <span style="border-bottom: 1px dotted #000; min-width: 100px; display: inline-block;">
                        {{ !in_array($workOrder->requisitioner_type, ['FACULTY', 'STAFF', 'STUDENT']) ? $workOrder->requisitioner_type : '' }}
                    </span>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td width="50%">
        <div style="margin-bottom: 5px;"><strong>Concern:</strong></div>
        
        <!-- Horizontal checkbox group for Concern -->
        <table cellspacing="0" cellpadding="0" style="border: none;">
            <tr>
                <!-- Enrollment -->
                <td style="padding-right: 20px; white-space: nowrap;">
                    <input type="checkbox" id="ENROLLMENT" name="concern" value="ENROLLMENT" {{ $workOrder->concern === 'ENROLLMENT' ? 'checked' : '' }} disabled>
                    <label for="ENROLLMENT">Enrollment</label>
                </td>
                
                <!-- Repair -->
                <td style="padding-right: 20px; white-space: nowrap;">
                    <input type="checkbox" id="REPAIR" name="concern" value="REPAIR" {{ $workOrder->concern === 'REPAIR' ? 'checked' : '' }} disabled>
                    <label for="REPAIR">Repair</label>
                </td>
                
                <!-- Other -->
                <td style="white-space: nowrap;">
                    <input type="checkbox" id="otherConcern" name="concern" value="other" {{ !in_array($workOrder->concern, ['ENROLLMENT', 'REPAIR']) ? 'checked' : '' }} disabled>
                    <label for="otherConcern">Other, specify:</label>
                    <span style="border-bottom: 1px dotted #000; min-width: 100px; display: inline-block;">
                        {{ !in_array($workOrder->concern, ['ENROLLMENT', 'REPAIR']) ? $workOrder->concern : '' }}
                    </span>
                </td>
            </tr>
        </table>
    </td>
</tr>
            <tr>
                <td colspan="2">
                    <div>Description of work order requested</div>
            
                    
                </td>

            </tr>
            <tr>
                <td colspan="2">
                <div style="min-height: 100px; white-space: pre-line; padding: 5px; ">{{ $workOrder->description }}</div>
                </td>
        </table>
        
        <!-- Signature section -->
        <div class="signatures">
            <div class="signature-column">
                <div class="signature-block">
                    <div class="signature-line">Signature over printed name</div>
                    <div>Requested by:</div>
                </div>
                
                <div class="signature-block">
                    <div class="signature-line">Head of Office/Department of Requisitioner</div>
                    <div>Recommending Approval:</div>
                </div>
            </div>
            
            <div class="signature-column">
                <div class="signature-block">
                    <div class="signature-line">Attending Personnel</div>
                    <div>Received by:</div>
                </div>
                
                <div class="signature-block">
                    <div class="signature-line">CARLO MARTIN A. SARAUSA</div>
                    <div>Approved by:</div>
                    <div>Director, DTO</div>
                </div>
            </div>
        </div>
        
        <!-- DTO Section -->
        <div class="dto-section">
            <div style="text-align: center; font-weight: bold; margin-bottom: 10px;">TO BE FILLED UP BY DTO</div>
            
            <div>Category Type</div>
            <div class="category-types">
                <div class="category-item">
                    <input type="radio" id="categoryI" name="category" value="Category I" {{ $workOrder->category === 'Category I' ? 'checked' : '' }} disabled>
                    <label for="categoryI">Category I (within one working day)</label>
                </div>
                <div class="category-item">
                    <input type="radio" id="categoryII" name="category" value="Category II" {{ $workOrder->category === 'Category II' ? 'checked' : '' }} disabled>
                    <label for="categoryII">Category II (1-3 working days)</label>
                </div>
                <div class="category-item">
                    <input type="radio" id="categoryIII" name="category" value="Category III" {{ $workOrder->category === 'Category III' ? 'checked' : '' }} disabled>
                    <label for="categoryIII">Category III (4-7 working days)</label>
                </div>
                <div class="category-item">
                    <input type="radio" id="categoryIV" name="category" value="Category IV" {{ $workOrder->category === 'Category IV' ? 'checked' : '' }} disabled>
                    <label for="categoryIV">Category IV (Unattainable)</label>
                </div>
            </div>
            
            <!-- Only show completed work description if status is Completed or Received -->
            @if($workOrder->status === 'Completed' || $workOrder->status === 'Received')
            <div style="margin-top: 15px;">
                <div>Description of completed work order</div>
                <div style="min-height: 100px; white-space: pre-line; padding: 5px; border: 1px solid #ccc;">{{ $workOrder->completed_description ?? '' }}</div>
            </div>
            
            <!-- Acceptance section -->
            <div style="margin-top: 20px; display: flex; justify-content: space-between;">
                <div style="width: 45%; text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 15px;">ACCEPTANCE</div>
                    <div style="margin-top: 40px;">
                        <div class="signature-line">{{ $workOrder->accepted_by ?? '' }}</div>
                        <div>Requisitioner:</div>
                    </div>
                    <div style="margin-top: 15px;">Date: ___________________</div>
                </div>
                
                <div style="width: 45%; text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 15px;">COMPLETED</div>
                    <div style="margin-top: 40px;">
                        <div class="signature-line">{{ $workOrder->completed_by ?? '' }}</div>
                        <div>Attending Personnel:</div>
                    </div>
                    <div style="margin-top: 15px;">Date: ___________________</div>
                </div>
            </div>
            @else
            <div style="margin-top: 15px;">Description of completed work order</div>
            <textarea name="completedWorkDescription" disabled></textarea>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div>CMU-F-4-DTO-001</div>
            <div>12 February 2024</div>
            <div>Rev. 2</div>
            <div>Page 1 of 1</div>
        </div>
    </div>
</body>
</html>