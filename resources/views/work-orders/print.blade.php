<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Order #{{ $workOrder->ticket_number }}</title>
     <style>
        /* Print-specific styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #printTable, #printTable * {
                visibility: visible;
            }
            #printTable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
            }
            .notincluded, .notincludedacceptance, .notincludedcompleted {
                display: none !important;
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

<body class="">
    <div class="pcoded-main-container">
        <div class="pcoded-content container">
            <div class="row">
                <div class="col-sm-12">
                    <form class="container" method="post">
                        <div id="workform">
                            <div class="printableborder card" id="printTable">
                                <div class="card-body">
                                    <div class="notincluded col-sm-12 row mb-3">
                                        <!-- <img src="assets/img/logo_cmu.png" alt="CMU Logo" class="img-fluid img-radius wid-100"> -->
                                        <div class="col-sm-10">
                                            <h5 class="text-secondary mb-0 mt-3">Republic of the Philippines</h5>
                                            <h4 class="mb-0">CENTRAL MINDANAO UNIVERSITY</h4>
                                            <h5 class="text-secondary">Musuan, Maramag, Bukidnon</h5>
                                        </div>
                                    </div>

                                    <div class="notincluded progress mb-1" style="height:2px;">
                                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <h4 class="notincluded mt-0">DIGITAL TRANSFORMATION OFFICE</h4>
                                    <div class="notincluded col-sm-12 mb-3 mt-4 text-center">
                                        <h4 class="mb-0">WORK ORDER REQUEST FORM</h4>
                                        <h5 class="text-secondary">(Services Only)</h5>
                                    </div>

                                    <div class="notincluded col-sm-12 mb-3 mt-4 row justify-content-between">
                                        <div class="col-sm-5 row">
                                            <div class="col-sm-12 row">
                                                <h5 class="text-secondary col-sm-4 mr-0">Ticket No.</h5>
                                                <div class="col-sm-6 pl-0">
                                                    <input type="text" class="text-secondary col-sm-12" style="border: none; border-bottom: solid black 1px;" name="ticket_no" value="{{ $workOrder->ticket_number }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="col-sm-12 row justify-content-end">
                                                <h5 class="text-secondary col-sm-3">Date:</h5>
                                                <div class="col-sm-6 pl-0">
                                                    <h5 class="text-secondary col-sm-12" style="border-bottom: solid black 1px;">{{ $workOrder->date_requested }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="notincluded col-sm-12">
                                        <div class="row">
                                            <div class="datta-example col-sm-2 pb-0 pt-2 text-center">
                                                <h6 class="text-secondary">Requested by</h6>
                                            </div>
                                            <div class="datta-example col-sm-10 pb-0 pt-2 text-center">
                                                <h5 class="text-secondary">{{ $workOrder->requested_by }}</h5>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="datta-example col-sm-2 pb-0 pt-2 text-center">
                                                <h6 class="text-secondary">College/Unit</h6>
                                            </div>

                                            <div class="datta-example col-sm-4 pb-0 pt-2 text-center">
                                                <h5 class="text-secondary">{{ $workOrder->college }}</h5>
                                            </div>

                                            <div class="datta-example col-sm-2 pb-0 pt-2 text-center">
                                                <h6 class="text-secondary">Department</h6>
                                            </div>

                                            <div class="datta-example col-sm-4 pb-0 pt-2 text-center">
                                                <h5 class="text-secondary">{{ $workOrder->department }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="notincluded datta-example col-sm-12 mt-3 pb-0 pt-2">
                                        <h5 class="text-secondary">Work order details</h5>
                                    </div>

                                    <div class="notincluded datta-example col-sm-12 pt-1 pb-1">
                                        <div class="custom-control custom-checkbox custom-control-inline col-sm-2">
                                            <input type="checkbox" class="custom-control-input input-dark" id="FACULTY" {{ $workOrder->requisitioner_type === 'FACULTY' ? 'checked' : '' }} disabled>
                                            <label class="custom-control-label" for="FACULTY">FACULTY</label>
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-inline col-sm-2">
                                            <input type="checkbox" class="custom-control-input input-dark" id="STAFF" {{ $workOrder->requisitioner_type === 'STAFF' ? 'checked' : '' }} disabled>
                                            <label class="custom-control-label" for="STAFF">STAFF</label>
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-inline col-sm-2">
                                            <input type="checkbox" class="custom-control-input input-dark" id="STUDENT" {{ $workOrder->requisitioner_type === 'STUDENT' ? 'checked' : '' }} disabled>
                                            <label class="custom-control-label" for="STUDENT">STUDENT</label>
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-inline col-sm-4">
                                            <input type="checkbox" class="custom-control-input input-dark" id="customCheckc7" {{ !in_array($workOrder->requisitioner_type, ['FACULTY', 'STAFF', 'STUDENT']) ? 'checked' : '' }} disabled>
                                            <label class="custom-control-label" for="customCheckc7">Others, please specify</label>
                                            <h6 class="text-secondary text-center col-sm-5 ml-2" style="border-bottom: solid black 1px;">{{ !in_array($workOrder->requisitioner_type, ['FACULTY', 'STAFF', 'STUDENT']) ? $workOrder->requisitioner_type : '' }}</h6>
                                        </div>
                                    </div>

                                    <div class="notincluded datta-example col-sm-12 pt-1 pb-1">
                                        <div class="custom-control custom-checkbox custom-control-inline col-sm-3">
                                            <input type="checkbox" class="custom-control-input input-dark" id="ENROLLMENT" {{ $workOrder->concern === 'ENROLLMENT' ? 'checked' : '' }} disabled>
                                            <label class="custom-control-label" for="ENROLLMENT">ENROLLMENT</label>
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-inline col-sm-3">
                                            <input type="checkbox" class="custom-control-input input-dark" id="REPAIR" {{ $workOrder->concern === 'REPAIR' ? 'checked' : '' }} disabled>
                                            <label class="custom-control-label" for="REPAIR">REPAIR</label>
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-inline col-sm-5">
                                            <input type="checkbox" class="custom-control-input input-dark" id="customCheckc7" {{ !in_array($workOrder->concern, ['ENROLLMENT', 'REPAIR']) ? 'checked' : '' }} disabled>
                                            <label class="custom-control-label" for="customCheckc7">Others, please specify</label>
                                            <h6 class="text-secondary text-center col-sm-7 ml-2" style="border-bottom: solid black 1px;">{{ !in_array($workOrder->concern, ['ENROLLMENT', 'REPAIR']) ? $workOrder->concern : '' }}</h6>
                                        </div>
                                    </div>
                                    <div class="notincluded datta-example col-sm-12 pb-0 pt-2">
    <h5 class="text-secondary">Category</h5>
</div>

<div class="notincluded datta-example col-sm-12 pt-1 pb-1">
    <div class="custom-control custom-checkbox custom-control-inline col-sm-3">
        <input type="checkbox" class="custom-control-input input-dark" id="categoryI" {{ $workOrder->category === 'Category I' ? 'checked' : '' }} disabled>
        <label class="custom-control-label" for="categoryI">Category I (1 day)</label>
    </div>
    <div class="custom-control custom-checkbox custom-control-inline col-sm-3">
        <input type="checkbox" class="custom-control-input input-dark" id="categoryII" {{ $workOrder->category === 'Category II' ? 'checked' : '' }} disabled>
        <label class="custom-control-label" for="categoryII">Category II (1–3 days)</label>
    </div>
    <div class="custom-control custom-checkbox custom-control-inline col-sm-3">
        <input type="checkbox" class="custom-control-input input-dark" id="categoryIII" {{ $workOrder->category === 'Category III' ? 'checked' : '' }} disabled>
        <label class="custom-control-label" for="categoryIII">Category III (4–7 days)</label>
    </div>
    <div class="custom-control custom-checkbox custom-control-inline col-sm-3">
        <input type="checkbox" class="custom-control-input input-dark" id="categoryIV" {{ $workOrder->category === 'Category IV' ? 'checked' : '' }} disabled>
        <label class="custom-control-label" for="categoryIV">Category IV (7+ days)</label>
    </div>
</div>


                                    <div class="notincluded datta-example col-sm-12 pb-0 pt-2">
                                        <h5 class="text-secondary">Description of work order requested</h5>
                                    </div>

                                    <div class="notincluded datta-example col-sm-12 mt-0">
                                        <p class="text-secondary mt-0 pt-0" style="height:50px; white-space: pre-line;">{{ $workOrder->description }}</p>
                                    </div>

                                    <!-- Signature sections remain unchanged as they're static -->
                                    <!-- ... rest of your template remains the same ... -->

                                    @if($workOrder->status === 'Completed' || $workOrder->status === 'Received')
                                    <div class="notincluded datta-example col-sm-12 pb-0 pt-2">
                                        <h5 class="text-secondary">Description of Completed work order</h5>
                                    </div>

                                    <div class="notincluded datta-example col-sm-12 mt-0">
                                        <p class="text-secondary mt-0 pt-0" style="height:50px; white-space: pre-line;">{{ $workOrder->completed_description ?? '' }}</p>
                                    </div>

                                    <div id="acceptance" class="col-sm-12">
                                        <div class="notincludedacceptance row">
                                            <div class="datta-example col-sm-6 pb-0 pt-2">
                                                <h5 class="text-secondary text-center">ACCEPTANCE</h5>
                                            </div>
                                            <div class="datta-example col-sm-6 pb-0 pt-2">
                                                <h5 class="text-secondary text-center">COMPLETED</h5>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="datta-example col-sm-4 pb-0 pt-2">
                                                <h5 class="notincludedacceptance text-secondary">Accepted by</h5>
                                                <h5 class="text-secondary text-center fortopmargin" style="height:10px;">{{ $workOrder->accepted_by ?? '' }}</h5>
                                                <div class="notincludedacceptance progress mb-1" style="height:2px;">
                                                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <h5 class="notincludedacceptance text-secondary text-center">Signature over printed name</h5>
                                            </div>

                                            <div class="fortopmargin datta-example col-sm-2 pb-0 pt-2 text-center">
                                                <h5 class="notincludedacceptance text-secondary">Date</h5>
                                            </div>

                                            <div class="datta-example col-sm-4 pb-0 pt-2">
                                                <h5 class="notincludedcompleted text-secondary">Attended Personnel</h5>
                                                <h5 class="print-com text-secondary text-center fortopmargin" style="height:10px;">{{ $workOrder->completed_by ?? '' }}</h5>
                                                <div class="notincludedcompleted progress mb-1" style="height:2px;">
                                                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <h5 class="notincludedcompleted text-secondary text-center">Signature over printed name</h5>
                                            </div>

                                            <div class="fortopmargin print-com datta-example col-sm-2 pb-0 pt-2 text-center">
                                                <h5 class="notincludedcompleted text-secondary">Date</h5>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="notincluded progress mb-1 mt-4" style="height:2px;">
                                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <div class="notincluded d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 text-body">CMU-F-4-DTO-001</h5>
                                        <h5 class="mb-0 text-body">22 December 2021</h5>
                                        <h5 class="mb-0 text-body">Rev 1</h5>
                                        <h5 class="mb-0 text-body">Page 1 of 1</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>