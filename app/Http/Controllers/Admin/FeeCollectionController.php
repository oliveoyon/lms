<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\FeePayment;
use App\Models\Admin\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class FeeCollectionController extends Controller
{
    public function collectFee($std_hash_id = null)
    {
        $student = ($std_hash_id) ? Student::where('std_hash_id', $std_hash_id)->first()->std_id : null;
        return view('dashboard.admin.feeCollection.feeCollection', ['student_id' => $student]);
    }
    public function generateBill(Request $request)
    {
        $uptomonth = date('F', strtotime($request->mon));
        $studentExists = DB::table('students')->where('std_id', $request->std_id)->exists();
        if ($studentExists) {
            $dueReport = DB::table('fee_collections as fc')
                ->select(
                    'fc.id as fee_collection_id',
                    's.std_name',
                    'fc.std_id',
                    'fc.due_date',
                    'fc.academic_year',
                    'fc.payable_amount',
                    'fc.fee_description',
                    'fc.fee_collection_status',
                    'fc.school_id',
                    DB::raw('MONTH(fc.due_date) as due_month'),
                    DB::raw('fc.payable_amount - COALESCE(SUM(fp.amount_paid), 0) as remaining_due_amount'),
                    DB::raw('COALESCE(SUM(fp.amount_paid), 0) as amount_paid')
                )
                ->leftJoin('fee_payments as fp', 'fc.id', '=', 'fp.fee_collection_id')
                ->leftJoin('students as s', 'fc.std_id', '=', 's.std_id')
                ->where('fc.is_paid', false)
                ->where('fc.std_id', $request->std_id);

            if (!is_null($request->ac)) {
                $dueReport->where('fc.academic_year', $request->ac);
            }

            if (!is_null($request->mon)) {
                $dueReport->whereMonth('fc.due_date', '<=', $request->mon);
            }

            $dueReport = $dueReport->groupBy('fc.id', 's.std_name', 'fc.std_id', 'fc.due_date', 'fc.academic_year', 'fc.payable_amount', 'fc.fee_description', 'fc.fee_collection_status', 'fc.school_id', 'due_month')
                ->orderBy('fc.due_date')
                ->orderBy('due_month')
                ->get();

            $html = '';
            $currentMonth = null;

            // Modal HTML (outside the loop)
            $html .= '<div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
            $html .= '<div class="modal-dialog modal-lg" role="document">';
            $html .= '<div class="modal-content">';
            $html .= '<div class="modal-header">';
            $html .= '<h5 class="modal-title" id="exampleModalLabel">Collect Bill</h5>';
            $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            $html .= '<span aria-hidden="true">&times;</span>';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '<div class="modal-body">';
            $html .= '<div id="modal-content"></div>'; // Container for dynamic content
            $html .= '</div>';
            $html .= '<div class="modal-footer">';
            $html .= '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $monthTotal = 0;
            foreach ($dueReport as $row) {
                $month = date('F Y', strtotime($row->due_date));
                $monthTotal = $monthTotal + $row->remaining_due_amount;


                if ($currentMonth !== $month) {
                    // Close the previous card if it's not the first one

                    if ($currentMonth !== null) {

                        $html .= '</table>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    // Start a new card
                    $html .= '<div class="card">';
                    $html .= '<div class="card-header" style="background-color:#223344; color:white; font-weight: bold">' . $month .  '</div>';
                    $html .= '<div class="card-body">';
                    $html .= '<table class="table">';
                    $html .= '<thead>';
                    $html .= '<tr>';
                    $html .= '<th>Total Payable</th>';
                    $html .= '<th>Amount Paid</th>';
                    $html .= '<th>Remaining</th>';
                    $html .= '<th>Description</th>';
                    $html .= '</tr>';
                    $html .= '</thead>';
                    $html .= '<tbody>';
                    if ($monthTotal == 0) {
                        $html .= '<h4 class="badge badge-success" style="font-size:14px;"><i class="far fa-clock"></i> Bill Paid for ' . $month . '</h4>';
                        // $html .= '<button type="button" class="btn btn-primary open-modal-btn" data-toggle="modal" data-target="#billModal" data-month="' . $month . '" data-std-id="' . $row->std_id . '" data-academic-year="' . $row->academic_year . '">Collect Bill</button>';


                    } else {
                        $html .= '<button type="button" class="btn btn-primary open-modal-btn" data-toggle="modal" data-target="#billModal" data-month="' . $month . '" data-std-id="' . $row->std_id . '" data-academic-year="' . $row->academic_year . '">Collect Bill</button>';
                    }
                }

                // Add row data to the current table
                $html .= '<tr>';
                $html .= '<td>' . $row->payable_amount . '</td>';
                $html .= '<td>' . $row->amount_paid . '</td>';
                $html .= '<td>' . $row->remaining_due_amount . '</td>';
                $html .= '<td>' . $row->fee_description . '</td>';
                $html .= '</tr>';

                $currentMonth = $month;
            }

            // Close the last card
            if ($currentMonth !== null) {
                $html .= '</tbody>';
                $html .= '</table>';
                $html .= '</div>';
                $html .= '</div>';
            }

            if ($dueReport->count() > 0) {
                return response()->json(['html' => $html]);
            } else {
                $html = '<h2 style="text-center">Bill Already Cleared up to '.$uptomonth.'</h2>';
                return response()->json(['html' => $html]);
            }
        } else {
            // Return response indicating that the student does not exist
            $html = '<h2 style="text-center">Student Not Found</h2>';
            return response()->json(['html' => $html]);
        }
    }

    public function fetchColletcData(Request $request)
    {
        // $requestData = $request->all();
        // $html = '<pre>' . htmlspecialchars(print_r($requestData, true)) . '</pre>';
        $numericMonth = date('n', strtotime($request->month));


        $dueReport = DB::table('fee_collections as fc')
            ->select(
                'fc.id as fee_collection_id',
                's.std_name',
                'fc.std_id',
                'fc.due_date',
                'fc.academic_year',
                'fc.payable_amount',
                'fc.fee_description',
                'fc.fee_collection_status',
                'fc.school_id',
                DB::raw('MONTH(fc.due_date) as due_month'),
                DB::raw('fc.payable_amount - COALESCE(SUM(fp.amount_paid), 0) as remaining_due_amount'),
                DB::raw('COALESCE(SUM(fp.amount_paid), 0) as amount_paid')
            )
            ->leftJoin('fee_payments as fp', 'fc.id', '=', 'fp.fee_collection_id')
            ->leftJoin('students as s', 'fc.std_id', '=', 's.std_id')
            ->where('fc.std_id', $request->stdId)
            ->where('fc.academic_year', $request->academicYear)
            ->whereMonth('fc.due_date', '<=', $numericMonth)
            ->groupBy('fc.id', 's.std_name', 'fc.std_id', 'fc.due_date', 'fc.academic_year', 'fc.payable_amount', 'fc.fee_description', 'fc.fee_collection_status', 'fc.school_id', 'due_month')
            ->orderBy('fc.due_date')
            ->orderBy('due_month')
            ->get();

        $html = '<pre>' . htmlspecialchars(print_r($dueReport, true)) . '</pre>';


        $html = '<form id="billForm" action="' . route('admin.collectBill') . '" method="POST">';
        $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '" />';
        $html .= '<table class="table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Payable Amount</th>';
        $html .= '<th>Paid Amount</th>';
        $html .= '<th>Remaining</th>';
        $html .= '<th>Description</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        // Loop through each row in the data
        foreach ($dueReport as $row) {
            if ($row->remaining_due_amount == 0) {
                continue;
            }
            $html .= '<tr>';
            $html .= '<td>' . $row->payable_amount . '</td>';
            $html .= '<td>' . $row->amount_paid . '</td>';
            $html .= '<td>' . $row->remaining_due_amount . '</td>';
            $html .= '<td>' . $row->fee_description . '</td>';
            $html .= '<input type="hidden" name="fee_collection_id[]" value="' . $row->fee_collection_id . '">';
            $html .= '<input type="hidden" name="remaining[]" value="' . $row->remaining_due_amount . '">';
            $html .= '<input type="hidden" name="std_id" value="' . $row->std_id . '">';
            $html .= '<input type="hidden" name="academic_year" value="' . $row->academic_year . '">';
            $html .= '</tr>';
        }

        // Add total row
        $html .= '<tr>';
        $html .= '<td colspan="2">Total</td>';
        $html .= '<td>' . $dueReport->sum('remaining_due_amount') . '</td>';

        $html .= '</tr>';

        // Add row for discount input


        // Add submit button
        if ($dueReport->sum('remaining_due_amount') == 0) {
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '<h2 style="bg-success">Already Paid</h2>';
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="2">Discount:</td>';
            $html .= '<td><input type="text" name="discount" class="form-control"></td>';
            $html .= '<td></td>';
            $html .= '</tr>';

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '<button type="submit" class="btn btn-primary">Submit</button>';
        }

        $html .= '</form>';



        // Return the HTML in a JSON response
        return response()->json(['html' => $html]);
    }

    public function collectBill(Request $request)
    {
        $trnx_id = time();

        for ($i = 0; $i < count($request->fee_collection_id); $i++) {
            $feeCollectionId = $request->fee_collection_id[$i];

            if ($request->remaining[$i] == 0) {
                continue;
            }

            $updateData = [
                'is_paid' => 1,
            ];
            DB::table('fee_collections')->where('id', $feeCollectionId)->update($updateData);

            $paymentData = [
                'fee_collection_id' => $feeCollectionId,
                'trnx_id' => $trnx_id,
                'amount_paid' => $request->remaining[$i],
                'payment_date' => now(),
                'payment_method' => 'Cash',
                'status' => 1,
                'school_id' => auth()->user()->school_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert a new record into the fee_payments table
            DB::table('fee_payments')->insert($paymentData);
        }

        // Reporting Starts

        $trnxId = $trnx_id;

        $send['payments'] = DB::table('fee_payments')
        ->select(
            'fee_payments.id',
            'fee_payments.fee_collection_id',
            'fee_payments.amount_paid',
            'fee_payments.payment_date',
            'fee_payments.payment_method',
            'fee_payments.trnx_id',
            'fee_payments.status',
            'fee_payments.school_id',
            'fee_collections.id as fee_collection_id',
            'fee_collections.fee_description',
            'students.std_name',
            'students.std_id',
            'edu_classes.class_name'
        )
        ->join('fee_collections', 'fee_payments.fee_collection_id', '=', 'fee_collections.id')
        ->join('students', 'fee_collections.std_id', '=', 'students.std_id')
        ->join('edu_classes', 'students.class_id', '=', 'edu_classes.id')
        ->where('fee_payments.trnx_id', $trnxId)
        ->get();

        $totalword = $send['payments']->sum('amount_paid');
        $numberService = new DependentController();
        $send['words'] = $numberService->numberToWords($totalword);

        $is_pos = false;
        $width = 0; //should be in database
        $height = 0; // should be in database

        $send['printmeta'] = [
            'fontsize' => 10,
            'width' => 80
        ];

        if($is_pos){
            return view('dashboard.admin.feeCollection.fee_invoice_pos', $send);
        }else{

            // $cr80Width = 80; //80 Width in millimeters
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-P',
                // 'format' => [190,236], //it will come from database
                // 'width' => 80,
                // 'margin_left' => 5,
                // 'margin_right' => 5,
                'margin_top' => 5,
                'margin_bottom' => 5,
            ]);

            $mpdf->SetWatermarkText('PAID');
            $mpdf->showWatermarkText = true;
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetAutoPageBreak(true);
            $mpdf->SetAuthor('IconBangla');
            $bladeViewPath = 'dashboard.admin.feeCollection.fee_invoice';
            $html = view($bladeViewPath, $send)->render();
            $mpdf->WriteHTML($html);
            return $mpdf->Output('invoice.pdf', 'I');

        }
    }
}
