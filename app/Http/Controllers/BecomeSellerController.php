<?php

namespace App\Http\Controllers;

use App\Models\BecomeSeller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class BecomeSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(BecomeSeller::query()->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'developer_type' => ['required','string'],
            'development_experience' => ['required', 'string'],
            'paypal_email' => ['required', 'unique:become_sellers,paypal_email'],
            'company_name' => ['required', 'string'],
            'billing_address' => ['required', 'string'],
            'billing_city' => ['required', 'string'],
            'billing_zip_postal_code' => ['required', 'string'],
            'VAT_number' => ['required', 'string'],
            'product_categories.*' => ['required', 'exists:product_categories,id'],
            'framework.*' => ['required', 'exists:frameworks,id']
        ]);
        try {
            $becomeSeller = new BecomeSeller();
            $becomeSeller->fill($request->except(['framework', 'product_categories']));
            $becomeSeller->user()->associate($request->user()->id);
            $becomeSeller->save();
            $becomeSeller->productCategories()->sync($request->product_categories);
            $becomeSeller->framework()->sync($request->framework);
            return response()->json("your become seller request has been farworded to admin");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BecomeSeller  $becomeSeller
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $becomeSeller = BecomeSeller::query()->with(['productCategories', 'framework'])->where('user_id', Crypt::decrypt($id))->first();
            return response()->json($becomeSeller);
//            return response()->json($becomeSeller->load(['operatingSystem', 'framework']));
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BecomeSeller  $becomeSeller
     * @return \Illuminate\Http\Response
     */
    public function edit(BecomeSeller $becomeSeller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BecomeSeller  $becomeSeller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BecomeSeller $becomeSeller)
    {
        $request->validate([
            'developer_type' => ['required','string'],
            'development_experience' => ['required', 'string'],
            'paypal_email' => ['required', Rule::unique('become_sellers')->ignore($becomeSeller)],
            'company_name' => ['required', 'string'],
            'billing_address' => ['required', 'string'],
            'billing_city' => ['required', 'string'],
            'billing_zip_postal_code' => ['required', 'string'],
            'VAT_number' => ['required', 'string'],
            'product_categories.*' => ['required', 'exists:product_categories,id'],
            'framework.*' => ['required', 'exists:frameworks,id']
        ]);
        try {
            $becomeSeller->fill($request->except(['framework', 'product_categories']));
            $becomeSeller->active = 0;
            $becomeSeller->user()->associate($request->user()->id);
            $becomeSeller->save();
            $becomeSeller->productCategories()->sync($request->product_categories);
            $becomeSeller->framework()->sync($request->framework);
            return response()->json("your become seller request has been farworded to admin");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BecomeSeller  $becomeSeller
     * @return \Illuminate\Http\Response
     */
    public function destroy(BecomeSeller $becomeSeller)
    {
        //
    }
}
