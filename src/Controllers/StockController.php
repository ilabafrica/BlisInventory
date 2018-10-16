<?php

namespace ILabAfrica\Inventory\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ILabAfrica\Inventory\Models\Stock;

class StockController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
            $stock = Stock::where('name', 'LIKE', "%{$search}%")
                ->paginate(10);
        } else {
            $stock = Stock::orderBy('id', 'ASC')->paginate(10);
        }

        return response()->json($stock);
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
            $stock = new Stock;
            $stock->lot_no = $request->input('lot_no');
            $stock->batch_no = $request->input('batch_no');
            $stock->expiry_date = $request->input('expiry_date');
            $stock->manufacturer = $request->input('manufacturer');
            $stock->supplier_id = $request->input('supplier_id');
            $stock->quantity_supplied = $request->input('quantity_supplied');
            $stock->cost_per_unit = $request->input('cost_per_unit');
            $stock->date_received = $request->input('date_received');
            $stock->remarks = $request->input('remarks');
            try {
                $stock->save();

                return response()->json($stock);
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
        $stock = Stock::findOrFail($id);

        return response()->json($stock);
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
            $stock = Stock::findOrFail($id);
            $stock->lot_no = $request->input('lot_no');
            $stock->batch_no = $request->input('batch_no');
            $stock->expiry_date = $request->input('expiry_date');
            $stock->manufacturer = $request->input('manufacturer');
            $stock->supplier_id = $request->input('supplier_id');
            $stock->quantity_supplied = $request->input('quantity_supplied');
            $stock->cost_per_unit = $request->input('cost_per_unit');
            $stock->date_received = $request->input('date_received');
            $stock->remarks = $request->input('remarks');

            try {
                $stock->save();

                return response()->json($stock);
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
            $stock = Stock::findOrFail($id);
            $stock->delete();

            return response()->json($stock, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

}
