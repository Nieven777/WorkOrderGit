<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Order #{{ $workOrder->ticket_number }}</title>
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            font-size: 12px;
        }

        .header-image {
            width: 100%;
            margin-bottom: 10px;
        }

        .header-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .form-group {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }

        .dotted-line {
            border-bottom: 1px dotted black;
            display: inline-block;
            min-width: 100px;
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

        .signatures {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .signature-column {
            width: 45%;
        }

        .signature-line {
            border-top: 2px solid black;
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            width: 200px;
        }

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
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }
        td {
            border: 1px solid black;
            padding: 5px;
        }
        .dotted-line {
            border-bottom: 1px dotted black;
            display: inline-block;
            min-width: 200px;
        }
        .signature-line {
            border-top: 1px solid black;
            margin-top: 30px;
            text-align: center;
            width: 100%;
        }
        .signature-title {
            text-align: center;
            font-weight: normal;
        }

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

            .header-image {
                width: 100% !important;
            }

            .header-image img {
                width: 100% !important;
                height: auto !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 500);
            }, 200);
        };
    </script>
</head>
<body>
    <div class="container" id="printTable">
        <!-- Full-width header image -->
        <div class="header-image">
            <img src="assets/img/formheader.png" alt="CMU Header">
        </div>

        <!-- Ticket and Date section -->
        <table style="width: 100%; margin-bottom: 10px; border-collapse: collapse; border: none;">
    <tr>
        <td style="text-align: left; width: 50%; border: none;">
            <strong>Ticket No:</strong>
            <span style="border-bottom: 1px solid #000; padding: 0 10px; display: inline-block; min-width: 120px;">
                {{ $workOrder->ticket_number }}
            </span>
        </td>
        <td style="text-align: right; width: 50%; border: none;">
            <strong>Date:</strong>
            <span style="border-bottom: 1px solid #000; padding: 0 10px; display: inline-block; min-width: 120px;">
                {{ $workOrder->date_requested }}
            </span>
        </td>
    </tr>
</table>






        <!-- Requester Info -->
        <table style="width: 100%; border-collapse: collapse; border: none;">
    <tr>
        <td width="15%"><strong>Requested by</strong></td>
        <td colspan="3">{{ $workOrder->requested_by }}</td>
    </tr>
    <tr>
        <td><strong>College/Unit</strong></td>
        <td>{{ $workOrder->college }}</td>
        <td width="15%"><strong>Department</strong></td>
        <td>{{ $workOrder->department }}</td>
    </tr>
</table>


        <!-- Work Order Details -->
        <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;"><strong>Work Order Details</strong></td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <input type="checkbox" {{ $workOrder->requisitioner_type === 'STUDENT' ? 'checked' : '' }} disabled> Student     
                <input type="checkbox" {{ $workOrder->requisitioner_type === 'FACULTY' ? 'checked' : '' }} disabled> Faculty    
                <input type="checkbox" {{ $workOrder->requisitioner_type === 'STAFF' ? 'checked' : '' }} disabled> Staff    
                <input type="checkbox" {{ !in_array($workOrder->requisitioner_type, ['FACULTY','STAFF','STUDENT']) ? 'checked' : '' }} disabled> Other, please specify
                <span class="dotted-line">{{ !in_array($workOrder->requisitioner_type, ['FACULTY','STAFF','STUDENT']) ? $workOrder->requisitioner_type : '' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <strong>Concern:</strong><br>
                <input type="checkbox" {{ $workOrder->concern === 'ENROLLMENT' ? 'checked' : '' }} disabled> Enrollment
                <input type="checkbox" {{ $workOrder->concern === 'REPAIR' ? 'checked' : '' }} disabled> Repair
                <input type="checkbox" {{ !in_array($workOrder->concern, ['ENROLLMENT','REPAIR']) ? 'checked' : '' }} disabled> Other, please specify
                <span class="dotted-line">{{ !in_array($workOrder->concern, ['ENROLLMENT','REPAIR']) ? $workOrder->concern : '' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;"><strong>Description of work order requested</strong></td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px; min-height: 150px;">
                <div style="min-height: 50px; white-space: pre-line;">{{ $workOrder->description }}</div>
            </td>
        </tr>
        <!-- Signatures - Fixed Layout with original variables preserved -->
        <tr>
            <td style="width: 50%; border: 1px solid black; padding: 3px; vertical-align: top;">
                <div>Requested by:</div>
                <div style="height: 10px;"></div>
                <div class="signature-line">Signature over printed name</div>
            </td>
            <td style="width: 50%; border: 1px solid black; padding: 3px; vertical-align: top;">
                <div>Received by:</div>
                <div style="height: 10px;"></div>
                <div class="signature-line">Attending Personnel</div>
            </td>
        </tr>
        <tr>
            <td style="width: 50%; border: 1px solid black; padding: 3px; vertical-align: top;">
                <div>Recommending Approval:</div>
                <div style="height: 10px;"></div>
                <div class="signature-line">Head of Office/Department of Requisitioner</div>
            </td>
            <td style="width: 50%; border: 1px solid black; padding: 3px; vertical-align: top;">
                <div>Approved by:</div>
                <div style="height: 5px;"></div>
                <div>CARLO MARTIN A. SARAUSA</div>
                <div class="signature-line"></div>
                <div class="signature-title">Director, DTO</div>
            </td>
        </tr>
    </table>

       

        <!-- DTO Section -->
        <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <tr>
      <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid black; padding: 5px; background-color: #f2f2f2;">TO BE FILLED UP BY DTO</td>
    </tr>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 10px;">
        <div style="display: flex; flex-wrap: wrap;">
          <div style="width: 50%;">
            <input type="checkbox" id="category1" style="transform: scale(1.2); margin-right: 8px;">
            <label for="category1" style="margin-right: 20px;">Category I (within one working day)</label>
          </div>
          <div style="width: 50%;">
            <input type="checkbox" id="category2" style="transform: scale(1.2); margin-right: 8px;">
            <label for="category2" style="margin-right: 20px;">Category II (1-3 working days)</label>
          </div>
        </div>
        <div style="display: flex; flex-wrap: wrap; margin-top: 10px;">
          <div style="width: 50%;">
            <input type="checkbox" id="category3" style="transform: scale(1.2); margin-right: 8px;">
            <label for="category3" style="margin-right: 20px;">Category III (4-7 working days)</label>
          </div>
          <div style="width: 50%;">
            <input type="checkbox" id="category4" style="transform: scale(1.2); margin-right: 8px;">
            <label for="category4" style="margin-right: 20px;">Category IV (Unattainable)</label>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">Description of completed work order</td>
    </tr>
    <tr>
      <td colspan="2" style="border: 1px solid black; height: 120px; vertical-align: top;"></td>
    </tr>
    <tr>
      <td style="width: 50%; text-align: center; font-weight: bold; border: 1px solid black; padding: 5px;">ACCEPTANCE</td>
      <td style="width: 50%; text-align: center; font-weight: bold; border: 1px solid black; padding: 5px;">COMPLETED</td>
    </tr>
    <tr>
      <td style="width: 50%; border: 1px solid black; vertical-align: top;">
        <table style="width: 100%; border-collapse: collapse;">
          <tr>
            <td style="width: 70%; border: none; padding: 5px;">Requisitioner:</td>
            <td style="width: 30%; border: none; padding: 5px;">Date</td>
          </tr>
          <tr>
            <td colspan="2" style="border: none; height: 80px; position: relative;">
              <div style="position: absolute; bottom: 20px; width: 100%; border-bottom: 1px solid black;"></div>
              <div style="position: absolute; bottom: 0; width: 100%; text-align: center;">Signature over printed name</div>
            </td>
          </tr>
        </table>
      </td>
      <td style="width: 50%; border: 1px solid black; vertical-align: top;">
        <table style="width: 100%; border-collapse: collapse;">
          <tr>
            <td style="width: 70%; border: none; padding: 5px;">Attending Personnel:</td>
            <td style="width: 30%; border: none; padding: 5px;">Date</td>
          </tr>
          <tr>
            <td colspan="2" style="border: none; height: 80px; position: relative;">
              <div style="position: absolute; bottom: 20px; width: 100%; border-bottom: 1px solid black;"></div>
              <div style="position: absolute; bottom: 0; width: 100%; text-align: center;">Signature over printed name</div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  
  <div style="font-size: 0.8em; margin-top: 20px; display: flex; justify-content: space-between;">
    <div>CMU-F-4-DTO-001</div>
    <div>12 February 2024</div>
    <div>Rev. 2</div>
    <div>Page 1 of 1</div>
  </div>
    </div>
</body>
</html>
