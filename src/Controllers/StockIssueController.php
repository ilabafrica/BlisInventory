<?php

namespace ILabAfrica\Inventory\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use ILabAfrica\Inventory\Models\Stock;
use ILabAfrica\Inventory\Models\ItemRequest;
use ILabAfrica\Inventory\Models\StockIssueRecord;

class StockIssueController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
            $stockIssue = StockIssueRecord::where('name', 'LIKE', "%{$search}%")
                ->paginate(10);
        } else {
            $stockIssue = StockIssueRecord::orderBy('id', 'ASC')->paginate(10);
        }

        return response()->json($stockIssue);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            //'number' => 'required',
            //'expiry' => 'required',
            //'instrument_id' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $requestedItem = ItemRequest::findOrFail($request->input('request_id'));
            $stockEntry = Stock::whereId($request->input('stock_id'))->first();
            if(($stockEntry->quantity_supplied-$stockEntry->quantity_issued) < $request->input('quantity')) {
                return response()->json([
                'message' => 'Issued stock greater than available in inventory',
                'status' => 422,
                ], 422);
            }

            $requestedItem->quantity_issued += $request->input('quantity');
            if($requestedItem->quantity_issued >= $requestedItem->quantity_requested){
                $requestedItem->status_id = 2;
            }
            $requestedItem->save();

            $stockEntry->quantity_issued += $request->input('quantity');
            $stockEntry->save();

            $stockIssue = new StockIssueRecord;
            $stockIssue->item_id = $requestedItem->item_id;
            $stockIssue->quantity_issued = $request->input('quantity');
            $stockIssue->date_issued = $request->input('date_issued');
            $stockIssue->requested_by = $requestedItem->requested_by;
            $stockIssue->issued_by = Auth::guard('api')->user()->id;
            $stockIssue->received_by = $request->input('received_by');
            $stockIssue->remarks = $request->input('remarks');
            try {
                $stockIssue->save();

                return response()->json($stockIssue);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stockIssue = StockIssueRecord::findOrFail($id);

        return response()->json($stockIssue);
    }

    public function stockDetails($id)
    {
        $stockIssue = StockIssueRecord::with('request')->whereItem_id($id)->get();

        return response()->json($stockIssue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  request
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            //'number' => 'required',
            //'expiry' => 'required',
            //'instrument_id' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $stockIssue = StockIssueRecord::findOrFail($id);
            $stockIssue->item_id = $request->input('item_id');
            $stockIssue->quantity_issued = $request->input('quantity_issued');
            $stockIssue->date_issued = $request->input('date_issued');
            $stockIssue->requested_by = $request->input('requested_by');
            $stockIssue->issued_by = $request->input('issued_by');
            $stockIssue->received_by = $request->input('received_by');
            $stockIssue->remarks = $request->input('remarks');

            try {
                $stockIssue->save();

                return response()->json($stockIssue);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $stockIssue = StockIssueRecord::findOrFail($id);
            $stockIssue->delete();

            return response()->json($stockIssue, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

}
