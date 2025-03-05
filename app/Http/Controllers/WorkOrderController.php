<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrder;
use Carbon\Carbon;

class WorkOrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate form fields
        $request->validate([
            'requested_by'       => 'required|string',
            'requisitioner_type' => 'required|string',
            'other_type'         => 'nullable|string',
            'college'            => 'required|string',
            'department'         => 'required|string',
            'concern'            => 'required|string',
            'other_concern'      => 'nullable|string',
            'date_requested'     => 'required|date',
            'description'        => 'required|string',
        ]);

        // Get the authenticated user and merge their ID into the request
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        // Determine prefix based on requisitioner type (case-insensitive)
        $requisitionerType = strtoupper($request->requisitioner_type);
        switch ($requisitionerType) {
            case 'STUDENT':
                $prefix = 'STU';
                break;
            case 'FACULTY':
                $prefix = 'FAC';
                break;
            case 'STAFF':
                $prefix = 'STF';
                break;
            default:
                $prefix = 'OTH';
                break;
        }

        // Count how many work orders have been submitted today using the date_requested field
        $today = date('Y-m-d');
        $countForToday = WorkOrder::whereDate('date_requested', $today)->count();
        $sequence = $countForToday + 1;
        $paddedSequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Get today's date in YYMMDD format for the ticket number
        $ticketDate = date('ymd');

        // Concatenate to create the ticket number (ensuring uniqueness)
        $ticketNumber = $prefix . $paddedSequence . $ticketDate;
        $request->merge(['ticket_number' => $ticketNumber]);

        // Create the work order record
        WorkOrder::create($request->all());

        return response()->json(['message' => 'Work order submitted successfully'], 201);
    }

        // For admin: Retrieve all work orders sorted by created_at (newest first)
        public function index(Request $request)
        {
            $workOrders = WorkOrder::orderBy('created_at', 'desc')->get();
            return response()->json($workOrders, 200);
        }
    
        // Update a work order's status (for admin)
        public function update(Request $request, $id)
        {
            // Validate that a valid status is provided
            $request->validate([
                'status' => 'required|in:Submitted,Received,Completed,Canceled'
            ]);
    
            $workOrder = WorkOrder::findOrFail($id);
            $workOrder->status = $request->input('status');
            $workOrder->save();
    
            return response()->json([
                'message' => 'Status updated successfully',
                'workOrder' => $workOrder
            ], 200);
        }
    

    public function show(Request $request)
    {
        // Retrieve the authenticated user
        $user = auth()->user();

        // Get all work orders for this user
        $workOrders = WorkOrder::where('user_id', $user->id)->get();

        return response()->json($workOrders, 200);
    }

    public function getWorkOrderCounts(Request $request)
    {
        // Count total work orders
        $total = WorkOrder::count();

        // Count in-progress orders (Submitted or Received)
        $inProgress = WorkOrder::whereIn('status', ['Submitted', 'Received'])->count();

        // Count completed work orders
        $completed = WorkOrder::where('status', 'Completed')->count();

        // Count cancelled work orders
        $cancelled = WorkOrder::where('status', 'Canceled')->count();

        return response()->json([
            'total'      => $total,
            'inProgress' => $inProgress,
            'completed'  => $completed,
            'cancelled'  => $cancelled,
        ], 200);
    }

        // Fetch today's work orders
        public function getTodaysWorkOrders(Request $request)
        {
            // Use Carbon to get today's date
            $today = Carbon::today();
    
            // Retrieve work orders where created_at is today, ordered descending (latest first)
            $orders = WorkOrder::whereDate('created_at', $today)
                               ->orderBy('created_at', 'desc')
                               ->get();
    
            return response()->json($orders, 200);
        }
}
