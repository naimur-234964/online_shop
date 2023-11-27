<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    public function index(Request $request){
        $brands = Brands::latest();

        if(!empty($request->get('keyword'))){
            $brands = $brands->where('name', 'like', '%'.$request->get('keyword').'%');
        }

        $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required | unique:brands',
        ]);

        if ($validator->passes()){
            $brand = new Brands();
            $brand->name = $request->name;
            $brand->slug = $request->slug;        
            $brand->status = $request->status;        
            $brand->save();

            $request->session()->flash('success', 'Brand created succesfully.');

            return response([
                'status' => true,
                'message' => 'Brand created succesfully',
            ]);
        }else{
            return response([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function create(){
        return view('admin.brands.create');
    }

    public function edit($id, Request $request) {
        $brand = Brands::find($id);

        if(empty($brand)){
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('brands.index');
        }

        
        $data['brand'] = $brand;
        return view('admin.brands.edit', compact('brand'));
    }

    public function update($id, Request $request){

        $brand = Brands::find($id);

        if(empty($brand)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
            // return redirect()->route('brands.index');
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required | unique:brands, slug, '.$brand->id.', id',
        ]);

        if ($validator->passes()){
            $brand = new Brands();
            $brand->name = $request->name;
            $brand->slug = $request->slug;        
            $brand->status = $request->status;        
            $brand->save();

            $request->session()->flash('success', 'Brand created succesfully.');

            return response([
                'status' => true,
                'message' => 'Brand created succesfully',
            ]);
        }else{
            return response([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
