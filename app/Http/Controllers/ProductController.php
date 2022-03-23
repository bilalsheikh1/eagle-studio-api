<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\Product;
use App\Models\ProductSubcategory;
use App\Models\ProductTemplate;
use App\Models\ProductView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if( isset($request->urn)) {
                if($request->urn == "sold-ready-2-use")
                    $productTemplate = ProductTemplate::query()->where("urn",'like','%ready-2-use%')->first();
                else
                    $productTemplate = ProductTemplate::query()->where("urn",'like','%'. $request->urn .'%')->first();

                if($request->urn == "ready-2-use")
                {
                    $product = Product::query()->with(['productTemplate' => function($q){
                        $q->where("urn","ready-2-use");
                    }, 'thumbnailImage', "productCategory",
                        "orders" => function ($q){
                            $q->doesntExist();
                        }])->withAvg("productRating","rating")->where("status", "1")->
                       paginate(48);
                }
                else if($request->urn == "sold-ready-2-use")
                {
                    $product = Product::query()->with(['productTemplate' => function($q){
                        $q->where("urn","ready-2-use");
                    }, 'thumbnailImage', "productCategory",
                        "orders" => function ($q){
                            $q->exists();
                        }])->withAvg("productRating","rating")->where("status", "1")->
                    paginate(48);
                }
                else
                {
                    $product = Product::query()->with(['productTemplate', 'thumbnailImage', "productCategory"])
                        ->withAvg("productRating","rating")->where("status", "1")->
                        where("product_template_id",$productTemplate->id)->paginate(48);
                }
                return $this->apiSuccess("",$product);
            }
            else
                return response()->json([]);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getProductsByStatus(Request $request)
    {
        $request->validate([
           "status" => ["required", "string"]
        ]);
        try {
            if ($request->status == "draft")
                return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems', 'thumbnailImage'])->where('status', '0')->where('user_id', $request->user()->id)->get());
            else if ($request->status == "live")
                return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems', 'thumbnailImage'])->where('status', '1')->where('user_id', $request->user()->id)->get());
            else if ($request->status == "pending")
                return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems', 'thumbnailImage'])->where('status', '3')->where('user_id', $request->user()->id)->get());
            else if ($request->status == "reject")
                return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems', 'thumbnailImage'])->where('status', '2')->where('user_id', $request->user()->id)->get());
            else if ($request->status == "all")
                return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems', 'thumbnailImage'])->where('user_id', $request->user()->id)->get());
        } catch (Exception $exception){
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
            return response()->json($product->paginate($request->pagination['pageSize']));
        } catch (Exception $exception){
            return response()->json($exception->getMessage(), 500);
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
            return response()->json("product has {$status}");
        } catch (Exception $exception){
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
                $product->where('title','LIKE', '%'.$request->filters['title'][0].'%');
            return response()->json($product->paginate($request->pagination['pageSize']));
        } catch (Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getProductByTemplate(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'urn' => ['required','string']
        ]);
        try {

            $productTemplate = ProductTemplate::query()->
            where('urn','like','%'. $request->urn .'%')->
            with('productSubcategories')->first();

            $product = Product::query()->
            with(['productTemplate', 'thumbnailImage', "productCategory"])->
            withAvg("productRating","rating")->
            where("status", "1")->where("product_template_id",$productTemplate->id)->paginate(48);

            if($productTemplate->urn == "ready-2-use")
            {
                $productTemplate->productSubcategories = ProductSubcategory::query()->get();
                $productTemplate->product_subcategories = ProductSubcategory::query()->get();
            }
            return $this->apiSuccess("",["productTemplate" => $productTemplate, "product" => $product]);
        } catch (Exception $exception){
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
            foreach ($request->template as $key => $value){
                if($value['type'] == "category"){
                    $product->whereHas('productCategory', function ($q) use ($value){
                        $q->where('product_categories.name', $value['name']);
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

                if($value['type'] == "title")
                    $product->where('title', 'LIKE', '%'. $value['name'] .'%');

                if(isset($request->urn))
                    $product->whereHas("productTemplate",function ($q) use ($request){
                        $q->where('urn', 'like', "%{$request->urn}%");
                    });
            }
            return response()->json($product->with(['productCategory', 'thumbnailImage'])->where("status", 1)->paginate(48));
        } catch (Exception $exception) {
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
        } catch (Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

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
            'features' => ['required'],
            'description' => ['required'],
        ]);

        try {
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
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getProductByTitle(Request $request)
    {
        $request->validate([
            'title' => ['string', 'required']
        ]);
        try{
            return response()->json(Product::query()->with(['thumbnailImage', 'productCategory'])->where("status", 1)->where('title', 'LIKE', '%'. $request->title .'%')->get());
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(),500);
        }
    }

    public function getProduct(Product $product)
    {
        try {
            return response()->json($product->load(['productTemplate', 'productCategory', 'productSubcategory', 'operatingSystems', 'framework', 'featuredImage', 'screenshots', 'thumbnailImage', 'file','user']));
        } catch (Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product): \Illuminate\Http\JsonResponse
    {
        try {
            if($product->status == 1) {
                $product->productViews()->create(["views" => 1, "product_id" => $product->id]);
                $product->update();
                return response()->json($product->load(['productTemplate', 'productRating' => function ($q){
                    $q->with("user");
                }, 'productCategory', 'productSubcategory', 'operatingSystems', 'framework', 'featuredImage', 'screenshots', 'thumbnailImage', 'file', 'user']));
            }
            return response()->json([]);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function approvedProduct(Product $product)
    {
        try {
            return response()->json($product->load(['productTemplate', 'productCategory', 'productSubcategory', 'operatingSystems', 'framework', 'featuredImage', 'screenshots', 'thumbnailImage', 'file','user'])->where('status', 1));
        } catch (Exception $exception) {
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

            if($request->step3) {
                $product->status = 3;
                if(!$product->file()->exists())
                    return response()->json("Product File required",422);
            }

            $product->fill($request->all());
            $product->save();
            return response()->json($product);
        } catch (Exception $exception) {
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
        }catch (Exception $exception){
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
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getProductsViews(Request $request)
    {
        try {
//            $product = Product::query()->with(["views" => function ($q) use ($request){
//                $q->where("created_at", Carbon::now()->subYear());
//            }])->get();
//            $viewsType = Carbon::now()->subYear();
//            if(isset($request->viewsType))
//            {
//                if($request->viewsType == "month")
//                    $viewsType = Carbon::now()->month();
//                if($request->viewsType == "daily")
//                    $viewsType = Carbon::now();
//            }
//            $data = DB::select("SELECT * FROM (SELECT COUNT(pv.views) , p.title, CONCAT(MONTHNAME(pv.`created_at`), ' ' ,YEAR(pv.`created_at`)) FROM `products` p JOIN `product_views` pv ON (p.`id` = pv.`product_id`) WHERE pv.`created_at` >= {$viewsType}  AND p.`user_id` = {$request->user()->id} GROUP BY YEAR(pv.`created_at`)) AS a ");
            $condition = "1";
            if(isset($request->product_id))
                $condition = "and product_id = {$request->product_id}";
            $data = DB::select("SELECT CONVERT(DAY(demo.d), NCHAR) AS date, IF(virtual.views, virtual.views, 0) AS views FROM (SELECT  DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH)) + INTERVAL t.n - 1 DAY AS d, t.n AS n FROM tally t) AS  demo LEFT JOIN (SELECT COUNT(pv.views) AS views,DATE(pv.created_at) AS DATE FROM `products` p JOIN `product_views` pv ON (p.`id` = pv.`product_id`) WHERE pv.`created_at` >= DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND 1 AND p.`user_id` = 3 GROUP BY DATE(pv.`created_at`)) AS virtual ON (demo.d = DATE(virtual.date)) WHERE demo.n <= DATEDIFF(DATE(DATE_SUB(NOW(), INTERVAL 2 DAY)), DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH))) + 1 ORDER BY demo.d");
            return $this->apiSuccess("",$data);
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

}
