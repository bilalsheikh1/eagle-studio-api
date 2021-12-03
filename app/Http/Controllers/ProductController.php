<?php

namespace App\Http\Controllers;

use App\Models\FeaturedImage;
use App\Models\File;
use App\Models\Product;
use App\Models\ProductSubcategory;
use App\Models\ProductTemplate;
use App\Models\Screenshot;
use App\Models\ThumbnailImage;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if($request->urn) {
                $product = ProductTemplate::query()->where('urn','like','%'. $request->urn .'%')->with(['products' => function($q) {
                    $q->where('status','1');
                }, 'products.productTemplate', 'products.thumbnailImage'])->get()->pluck('products');
                if(count($product) > 0)
                    return response()->json($product[0]);
                return response()->json([]);
            }
            if($request->status == "draft")
                return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems', 'thumbnailImage'])->where('status', '0')->get());
            else if($request->status == "live")
                return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems', 'thumbnailImage'])->where('status', '=','1')->get());
            else
                return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems', 'thumbnailImage'])->paginate($request->pageSize));
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getFilteredProducts(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            $product = Product::query()->with(['productTemplate', 'framework', 'productCategory', 'thumbnailImage']);
            if(isset($request->filters['product_template']))
                $product->whereIn('product_template_id', $request->filters['product_template']);
            if(isset($request->filters['framework']))
                $product->whereIn('framework_id', $request->filters['framework']);
            if(isset($request->filters['product_category']))
                $product->whereIn('product_category_id', $request->filters['product_category']);
            if(isset($request->filters['title']))
                $product->where('title', 'LIKE', '%'.$request->filters['title'][0].'%');
            return \response()->json($product->paginate($request->pagination['pageSize']));
        } catch (\Exception $exception){
            return \response()->json($exception->getMessage(), 500);
        }
    }

    public function getRequests($status, Request $request): \Illuminate\Http\JsonResponse
    {
//        'productSubcategory', 'operatingSystems'
        return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'thumbnailImage'])->where('status', $status)->paginate($request->pageSize));
    }

    public function mutateRequest(Product $product, $status): \Illuminate\Http\JsonResponse
    {
        try {
            $product->status = $status;
            $product->update();
            $status = ( $status == "0") ? "been pending" : ($status == "1" ? "been approved" : ($status == "2" ? "been rejected" : 'not update'));
            return \response()->json("product has {$status}");
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function filteredProductRequest($status, Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            $product = Product::query()->with(['productTemplate', 'framework', 'productCategory', 'thumbnailImage'])
                ->where('status', $status);
            if(isset($request->filters['product_template']))
                $product->whereIn('product_template_id', $request->filters['product_template']);
            if(isset($request->filters['framework']))
                $product->whereIn('framework_id', $request->filters['framework']);
            if(isset($request->filters['product_category']))
                $product->whereIn('product_category_id', $request->filters['product_category']);
            if(isset($request->filters['title']))
                $product->where('title', 'LIKE', '%'.$request->filters['title'][0].'%');
            return \response()->json($product->paginate($request->pagination['pageSize']));
        } catch (\Exception $exception){
            return \response()->json($exception->getMessage(), 500);
        }
    }

    public function getProductByTemplate(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'urn' => ['required','string']
        ]);
        try {
            $product = ProductTemplate::query()->where('urn','like','%'. $request->urn .'%')->with('productSubcategories')->get()->pluck('productSubcategories');
            if(count($product) > 0)
                return response()->json($product[0]);
            return response()->json("data not found");
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getFilterProduct(Request $request): \Illuminate\Http\JsonResponse
    {
//        $request->validate([
//            'type' => ['required', 'string']
//        ]);
        try {
            $product = Product::query();
            $checkType = "";
            foreach ($request->all() as $key => $value){
                $checkType = $value['type'];
                if($value['type'] == "category"){
                    $product->whereHas('productCategory', function ($q) use ($value){
                        $q->where('product_categories.id', $value['id']);
                    });
                }
                if($value['type'] == "subCategory"){
                    $product->whereHas('productSubcategory', function ($q) use ($value){
                        $q->where('product_subcategories.id', $value['id']);
                    });
                }
                if($value['type'] == "price"){
                    $product->whereBetween('single_app_license', $value['price']);
                }
                if(isset($value['name'])){
                    if($value['name']!= "")
                        $product->where('title', 'LIKE', '%'. $value['name'] .'%');
                }
            }
            if($checkType == "category" || $checkType == "subCategory" || $checkType == "price" || $checkType == "name") {
                return response()->json($product->with(['productCategory', 'thumbnailImage'])->get());
            }

            if(isset($request->urn)) {
                $product = ProductTemplate::query()->where('urn', 'like', '%' . $request->urn . '%')->with(['products', 'products.productTemplate', 'products.thumbnailImage'])->get()->pluck('products');
                if (count($product) > 0)
                    return response()->json($product[0]);
            }
            return response()->json([]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getProductBySubCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'urn' => ['required','string']
        ]);
        try {
            $product = ProductTemplate::query()->where('urn','like','%'. $request->urn .'%')->get();
            if(count($product) > 0)
                return response()->json("data not found");
            return response()->json([]);
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

//    public function

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'unique:products,title'],
            'product_template' => ['required', 'exists:product_templates,id'],
            'product_category' => ['required', 'exists:product_categories,id'],
            'product_subcategory.*' => ['required', 'exists:product_subcategories,id'],
            'operating_systems.*' => ['required', 'exists:operating_systems,id'],
            'framework' => ['required', 'exists:frameworks,id'],
            'description' => ['required'],
/*            'features' => ['required'],
            'featured_image' => ['required', 'dimensions:width=650,height=290'],
            'thumbnail_image' => ['required', 'dimensions:width=200,height=140'],
            'screenshots.*' => ['required', 'image'],
            'youtube_link' => ['nullable', 'URL'],
            'google_play_link' => ['nullable', 'URL'],
            'app_store_link' => ['nullable', 'URL'],
            'files.*' => ['required', 'mimes:zip,rar'],
            'single_app_license' => ['required'],
            'multi_app_license' => ['required'],
            'development_hours' => ['numeric']*/
        ]);

//        try {
            $product = new Product;
            $product->fill($request->all());
            $product->user()->associate($request->user());
            $product->productTemplate()->associate($request->product_template);
            $product->framework()->associate($request->framework);
            $product->productCategory()->associate($request->product_category);
            $product->save();
            $product->productSubcategory()->sync($request->product_subcategory);
            $product->operatingSystems()->sync($request->operating_systems);
            return response()->json($product);
//        } catch (\Exception $exception) {
//            return response()->json($exception->getMessage(), 500);
//        }
    }

    public function getProductByTitle(Request $request)
    {
        $request->validate([
            'title' => ['string', 'required']
        ]);
        try{
            return response()->json(Product::query()->with(['thumbnailImage', 'productCategory'])->where('title', 'LIKE', '%'. $request->title .'%')->get());
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),500);
        }
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json($product->load(['productTemplate', 'productCategory', 'productSubcategory', 'operatingSystems', 'framework', 'featuredImage', 'screenshots', 'thumbnailImage', 'file','user'])->where('status', 1));
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        if($request->step3) {
            $request->validate([
                'single_app_license' => ['required'],
                'multi_app_license' => ['required'],
                'development_hours' => ['required', 'numeric'],
            ]);
        } else {
            $request->validate([
                'youtube_link' => ['nullable', 'URL'],
                'google_play_link' => ['nullable', 'URL'],
                'app_store_link' => ['nullable', 'URL'],
            ]);
        }

        try {
//            if($request->step3) {
//                $product->single_app_license = $request->single_app_license;
//                $product->multi_app_license = $request->multi_app_license;
//                $product->development_hours = $request->develop_hours;
//            } else {
//                $product->youtube_link = $request->youtube_link;
//                $product->google_play_link = $request->google_play_link;
//                $product->app_store_link = $request->app_store_link;
//            }
            if($request->product_template)
                $product->productTemplate()->associate($request->product_template);
            if($request->framework)
                $product->framework()->associate($request->framework);
            if($request->product_category)
                $product->productCategory()->associate($request->product_category);
            if($request->product_subcategory)
                $product->productSubcategory()->sync($request->product_subcategory);
            if($request->operating_systems)
                $product->operatingSystems()->sync($request->operating_systems);

            $product->fill($request->all());
            $product->save();
            return response()->json($product);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getFilteredData(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'Platform', ['nullable'],
            'Template', ['nullable'],
            'Subcategory', ['nullable'],
        ]);
        try{
            $product = Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems', 'thumbnailImage']);
            if($request->Platform)
                $product->where('product_category_id', $request->Platform['id']);
            if($request->Template)
                $product->where('product_template_id', $request->Template['id']);
            if($request->Subcategory)
                $product->where('product_subcategory_id', $request->Subcategory['id']);
            return response()->json($product->get());
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product): \Illuminate\Http\JsonResponse
    {
        try {
            $product->delete();
            return response()->json("Product has been deleted.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

}
