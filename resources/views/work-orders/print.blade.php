<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Order #{{ $workOrder->ticket_number }}</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header styles */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            color: #7f8c8d;
            font-size: 16px;
        }
        
        /* Details section */
        .details-section {
            margin-bottom: 30px;
        }
        
        .details-section h2 {
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        /* Table styles */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .details-table th,
        .details-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .details-table th {
            background-color: #f5f5f5;
            width: 30%;
        }
        
        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 14px;
            font-weight: bold;
        }
        
        /* Print-specific styles */
        @media print {
            body {
                padding: 0;
                font-size: 12pt;
            }
            
            .no-print {
                display: none;
            }
            
            .header {
                border-bottom: 2px solid #000;
            }
            
            .details-table th,
            .details-table td {
                border-bottom: 1px solid #000;
            }
        }
    </style>
    <script>
        // Auto-trigger print dialog when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
                
                // Close the window after a delay if print is completed
                setTimeout(function() {
                    window.close();
                }, 500);
            }, 200);
        });
        
        // Fallback for browsers that block window.close()
        window.addEventListener('afterprint', function() {
            setTimeout(function() {
                window.close();
            }, 100);
        });
    </script>
</head>
<body>
    <div class="header">
        <h1>WORK ORDER</h1>
        <p class="subtitle">Ticket #{{ $workOrder->ticket_number }}</p>
        
        <div style="margin-top: 15px;">
            <span class="status-badge" style="background-color: 
                @if($workOrder->status === 'Completed') #2ecc71
                @elseif($workOrder->status === 'Received') #f39c12
                @elseif($workOrder->status === 'Declined') #e74c3c
                @else #3498db
                @endif;
                color: white;">
                {{ $workOrder->status }}
            </span>
        </div>
    </div>
    
    <div class="details-section">
        <h2>Request Details</h2>
        <table class="details-table">
            <tr>
                <th>Requested By</th>
                <td>{{ $workOrder->requested_by }}</td>
            </tr>
            <tr>
                <th>Department</th>
                <td>{{ $workOrder->department }}</td>
            </tr>
            <tr>
                <th>College/Unit</th>
                <td>{{ $workOrder->college }}</td>
            </tr>
            <tr>
                <th>Date Requested</th>
                <td>{{ $workOrder->date_requested }}</td>
            </tr>
            <tr>
                <th>Concern Type</th>
                <td>{{ $workOrder->concern }}</td>
            </tr>
        </table>
    </div>
    
    <div class="details-section">
        <h2>Work Description</h2>
        <div style="white-space: pre-line; padding: 10px; background-color: #f9f9f9; border-radius: 4px;">
            {{ $workOrder->description }}
        </div>
    </div>
    
    @if($workOrder->status === 'Completed' || $workOrder->status === 'Received')
    <div class="details-section">
        <h2>Completion Details</h2>
        <table class="details-table">
            @if($workOrder->accepted_by)
            <tr>
                <th>Accepted By</th>
                <td>{{ $workOrder->accepted_by }}</td>
            </tr>
            @endif
            @if($workOrder->completed_by)
            <tr>
                <th>Completed By</th>
                <td>{{ $workOrder->completed_by }}</td>
            </tr>
            @endif
            @if($workOrder->completed_description)
            <tr>
                <th>Work Performed</th>
                <td style="white-space: pre-line;">{{ $workOrder->completed_description }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif
    
</body>
</html>