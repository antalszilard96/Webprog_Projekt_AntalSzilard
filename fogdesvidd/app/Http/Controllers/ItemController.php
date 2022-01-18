<?php

namespace App\Http\Controllers;


use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect,Response,File;

class ItemController extends Controller
{
    public function index(){
        return view('home.index');
    }

    
    public function fetchitem(){
        $items = Item::all();
        return response()->json([
            'items' => $items
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required',
            "phone"=>'required|digits:10',
            "itemName"=>'required',
            "itemDescription"=>'required',
            "image"=>'required|mimes:jpeg,jpg,png,gif|required|max:30000',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $item = new Item;
            $item->name=$request->input('name');
            $item->phone=$request->input('phone');
            $item->itemName=$request->input('itemName');
            $item->itemDescription=$request->input('itemDescription');
            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' .$extension;
                $file->move('uploads/images/', $filename);
                $item->image = $filename;
            }
            $item->save();
        
            return response()->json([
                'status'=>200,
                'message'=>'Sikeres hozzaadas',
            ]);                          
        }
    }
    public function edit($id){
        $item = Item::find($id);
        if($item)
        {
            return response()->json([
                'status'=>200,
                'item'=>$item,
            ]);             
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=> 'Az item nem talalhato',
            ]);
        }

    }
    public function update(Request $request, $id){
        
            $item = Item::find($id);
             Item::where('id',$id)->update(['busy'=>'igen']);

             if($item->busy=='igen'){
                return response()->json([
                    'status'=>200,
                    'message'=>'Mar foglalt!!',
                ]);      
             }
             return response()->json([
                'status'=>200,
                'message'=>'Sikeres foglalas',
            ]);      
    }
  
}
