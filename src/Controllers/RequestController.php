<?php

namespace ILabAfrica\Inventory\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ILabAfrica\Inventory\Models\ItemRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
            $item_reqest = ItemRequest::with('item', 'lab', 'user', 'status')->where('name', 'LIKE', "%{$search}%")
                ->get();
        } else {
            $item_reqest = ItemRequest::with('item', 'lab', 'user', 'status')->orderBy('id', 'ASC')->get();
        }

        return response()->json($item_reqest);
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
            $item_reqest = new ItemRequest;
            $item_reqest->item_id = $request->input('item_id');
            $item_reqest->curr_bal = $request->input('curr_bal');
            $item_reqest->lab_section_id = $request->input('lab_section_id');
            $item_reqest->tests_done = $request->input('tests_done');
            $item_reqest->quantity_requested = $request->input('quantity_requested');
            $item_reqest->requested_by = Auth::guard('api')->user()->id;
            $item_reqest->remarks = $request->input('remarks');
            try {
                $item_reqest->save();

                return response()->json($item_reqest);
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
        $item_reqest = ItemRequest::findOrFail($id);

        return response()->json($item_reqest);
    }

    public function requestIssue($id)
    {
        $item_reqest = ItemRequest::with('item', 'lab', 'user', 'status')->whereItem_id($id)->whereRaw('quantity_requested > quantity_issued')->get();

        return response()->json($item_reqest);
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
            $item_reqest = ItemRequest::findOrFail($id);
            $item_reqest->item_id = $request->input('item_id');
            $item_reqest->curr_bal = $request->input('curr_bal');
            $item_reqest->lab_section_id = $request->input('lab_section_id');
            $item_reqest->tests_done = $request->input('tests_done');
            $item_reqest->quantity_requested = $request->input('quantity_requested');
            $item_reqest->remarks = $request->input('remarks');

            try {
                $item_reqest->save();

                return response()->json($item_reqest);
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
            $item_reqest = Iitem_reqesttemRequest::findOrFail($id);
            $item_reqest->delete();

            return response()->json($item_reqest, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

}
