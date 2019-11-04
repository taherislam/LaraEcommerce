<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input; //upload image
use Auth;
use Session;
use Image;  //upload image
use App\Category;
use App\Product;
use App\ProductsAttribute;

class ProductsController extends Controller
{
    public function addProduct(Request $request){
      if ($request->isMethod('post')) {
        $data = $request->all();
        // dd($data);
        if (empty($data['category_id'])) {
            return redirect()->back()->with('flash_message_error', 'Under Category is Missing!');
        }
        $product = new Product;
        $product->category_id = $data['category_id'];
        $product->product_name = $data['product_name'];
        $product->product_code = $data['product_code'];
        $product->product_color = $data['product_color'];
        if (!empty($data['description'])) {
            $product->description = $data['description'];
        }else{
            $product->description = ' ';
        }
        $product->price = $data['price'];
        //Upload Image.............
        if ($request->hasFile('image')) {
          $image_tmp = Input::file('image');
          if ($image_tmp->isValid()) {
            $extension = $image_tmp->getClientOriginalExtension();
            $filename = rand(111,99999).'.'.$extension;
            $large_image_path = 'images/backend_images/products/large/'.$filename;
            $medium_image_path = 'images/backend_images/products/medium/'.$filename;
            $small_image_path = 'images/backend_images/products/small/'.$filename;
            //Resize Image code.......
            Image::make($image_tmp)->save($large_image_path);
            Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
            Image::make($image_tmp)->resize(300, 300)->save($small_image_path);
            //store image iame in path image........
            $product->image = $filename;
          }
        }
        //end image....
        $product->save();
        // return redirect()->back()->with('flash_message_success', 'Product has been added Successfully!');
          return redirect('/admin/view-products')->with('flash_message_success', 'Product has been added Successfully!');

      }
      // categories drop down start ............
      // $categories=Category::orderBy('id','desc')->get();
      $categories = Category::where(['parent_id'=>0])->get();
      $categories_dropdown = " <option selected disabled>Select</option> ";
      foreach ($categories as $cat) {
          $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
          //Sub category ............
          $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
          foreach ($sub_categories as $sub_cat) {
            $categories_dropdown .= "<option value='".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
          }
            //end Sub Category ............
      }
  // End  categories drop down start ............
      return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }
//........................  end add product   ....................................

// Edit Or Update  product   ....................................
  public function editProduct(Request $request, $id = null){
    if ($request->isMethod('post')) {
      $data = $request->all();
      /* dd($data); */
      //update Image file.............
      if ($request->hasFile('image')) {
        $image_tmp = Input::file('image');
        if ($image_tmp->isValid()) {
          $extension = $image_tmp->getClientOriginalExtension();
          $filename = rand(111,99999).'.'.$extension;
          $large_image_path = 'images/backend_images/products/large/'.$filename;
          $medium_image_path = 'images/backend_images/products/medium/'.$filename;
          $small_image_path = 'images/backend_images/products/small/'.$filename;
          //Resize Image code.......
          Image::make($image_tmp)->save($large_image_path);
          Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
          Image::make($image_tmp)->resize(300, 300)->save($small_image_path);

        }
      }else{
        $filename = $data['current_image'];
      }
    //end image.................
      if (empty($data['description'])) {
        $data['description'] = ' ';
      }

      Product::where(['id'=>$id])->update([
              'category_id'=>$data['category_id'],
              'product_name'=>$data['product_name'],
              'product_code'=>$data['product_code'],
              'product_color'=>$data['product_color'],
              'description'=>$data['description'],
              'price'=>$data['price'],
              'image'=>$filename
            ]);
     // return redirect()->back()->with('flash_message_success', 'Product has been Updated Successfully!');
       return redirect('/admin/view-products')->with('flash_message_success', 'Product has been Updated Successfully!');
    }
      $productDetails = Product::where(['id'=>$id])->first();
      // categories drop down start ---------------------
      $categories = Category::where(['parent_id'=>0])->get();
      $categories_dropdown = " <option selected disabled>Select</option> ";
      foreach ($categories as $cat) {
        if ($cat->id == $productDetails->category_id) {
          $selected = 'selected';
        }else{
          $selected = ' ';
        }
          $categories_dropdown .= "<option value='".$cat->id."' ".$selected." >".$cat->name."</option>";
          //Sub category ............
          $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
          foreach ($sub_categories as $sub_cat) {
            if ($sub_cat->id == $productDetails->category_id) {
              $selected = 'selected';
            }else{
              $selected = ' ';
            }
            $categories_dropdown .= "<option value='".$sub_cat->id."'".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
          }
      }
  // End  categories drop down start -----------------------
      return view('admin.products.edit_product')->with(compact('productDetails', 'categories_dropdown'));
  }
// End Edit Or Update  product   ....................................
// view products .................
  public function viewProducts(){
      $products = Product::Orderby('id', 'DESC')->get();
      // show category name......
      foreach ($products as $key => $value) {
          $category_name = Category::where(['id'=>$value->category_id])->first();
          $products[$key]->category_name = $category_name->name;

      }
      // category name show
      // echo "<pre>"; print_r($products); die;
      return view('admin.products.view_products')->with(compact('products'));
}
// view products .................
// Delete product....................................................
  public function deleteProduct($id = null){
    Product::where(['id'=>$id])->delete();
    return redirect()->back()->with('flash_message_success', 'Product has been Deleted Successfully!');
  }
//End Delete product....................................................
/* Delete deleteProductImage controller  */
public function deleteProductImage($id = null){
  Product::where(['id'=>$id])->update(['image'=>'']);
  return redirect()->back()->with('flash_message_success', 'Product Image has been Deleted Successfully!');
}
/* End of Delete product controller  */

/* Product Attributes Controller  */
public function addAttributes(Request $request, $id = null){
  // dd('test');
  $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
  // $productDetails = json_decode(json_encode($productDetails));
  // $productDetails = Product::where(['id'=>$id])->first();
      // dd($productDetails);
  if ($request->isMethod('post')) {
    $data = $request->all();
    // dd($data);
    foreach ($data['sku'] as $key => $val) {
      if (!empty($val)) {
        $attribute = new ProductsAttribute;
        $attribute->product_id = $id;
        $attribute->sku = $val;
        $attribute->size = $data['size'][$key];
        $attribute->price = $data['price'][$key];
        $attribute->stock = $data['stock'][$key];
        $attribute->save();
      }
    }
    return redirect('admin/add-attributes/'.$id)->with('flash_message_success', 'Product Attributes has been added Successfully!');
  }
  return view('admin.products.add_attributes')->with(compact('productDetails'));
}
// Delete Attributes Controller............................
public function deleteAttribute($id = null){
  ProductsAttribute::where(['id'=>$id])->delete();
  return redirect()->back()->with('flash_message_success', 'Product Attributes has been Deleted Successfully!');
}

public function products($url = null){
  //Get all Category and Sub Categories....
  $categories = Category::with('categories')->where(['parent_id'=>0])->get();
  $categoryDetails = Category::where(['url' => $url])->first();
  if ($categoryDetails->parent_id == 0) {
    // IF Url is Main Category url
    $subCategories = Category::Where(['parent_id'=>$categoryDetails->id])->get();
    foreach ($subCategories as $subcat) {
      $cat_ids []= $subcat->id;
      // echo $cat_ids; die;
    }
      $productsAll = Product::whereIn('category_id', $cat_ids)->get();
  }else {
      // IF Url is sub Category url
      $productsAll = Product::where(['category_id' => $categoryDetails->id])->get();
  }

  // dd($categoryDetails->id);
  return view('products.listing')->with(compact('categories', 'categoryDetails', 'productsAll'));
}






}
