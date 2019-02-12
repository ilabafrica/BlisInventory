<?php

namespace ILabAfrica\Inventory\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ILabAfrica\Inventory\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
            $items = Item::with('stock')->where('name', 'LIKE', "%{$search}%")
                ->paginate(10);
        } else {
            $items = Item::with('stock')->orderBy('id', 'ASC')->paginate(10);
        }
        foreach ($items as $key => $item) {
            $item->stockValue = $item->stockValue ;
        }

        return response()->json($items);
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
            $item = new Item;
            $item->name = $request->input('name');
            $item->unit = $request->input('unit');
            $item->min = $request->input('min');
            $item->max = $request->input('max');
            $item->storage_req = $request->input('storage_req');
            $item->remarks = $request->input('remarks');
            try {
                $item->save();

                return response()->json($item);
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
        $item = Item::findOrFail($id);

        return response()->json($item);
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
            $item = Item::findOrFail($id);
            $item->name = $request->input('name');
            $item->unit = $request->input('unit');
            $item->min = $request->input('min');
            $item->max = $request->input('max');
            $item->storage_req = $request->input('storage_req');
            $item->remarks = $request->input('remarks');

            try {
                $item->save();

                return response()->json($item);
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
            $item = Item::findOrFail($id);
            $item->delete();

            return response()->json($item, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

}
