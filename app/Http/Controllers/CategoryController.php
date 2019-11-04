<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
// Category Add....................................
    public function addCategory(Request $request){
      if ($request->isMethod('post')) {
        $data = $request->all();
        // dd();
        $category = new Category();
        $category->name = $data['category_name'];
        $category->parent_id = $data['parent_id'];
        $category->description = $data['description'];
        $category->url = $data['url'];
        $category->save();

        return redirect('/admin/view-categories')->with('flash_message_success', 'Category Added Successfully !');
      }
      $levels = Category::where(['parent_id'=>0])->get();   //drop down menu..........
      return view('admin.categories.add_category')->with(compact('levels'));
    }

// Category Edit/Update....................................
    public function editCategory(Request $request, $id = null){
      if ($request->isMethod('post')) {
        $data = $request->all();
        // dd($data);
        Category::where(['id'=>$id])->update(['name'=>$data['category_name'], 'description'=>$data['description'], 'url'=>$data['url']]);
        return redirect('/admin/view-categories')->with('flash_message_success', 'Category Updated Successfully!');
      }
      $categoryDetails = Category::where(['id'=>$id])->first();
        $levels = Category::where(['parent_id'=>0])->get(); //drop down menu.............
      return view('admin.categories.edit_category')->with(compact('categoryDetails', 'levels'));
        // dd();
    }

// Category delete.......................................
      public function deletCategory($id = null){
        // dd($id);
        if (!empty($id)) {
          Category::where(['id'=>$id])->delete();
          return redirect()->back()->with('flash_message_success', 'Category Delete Successfully!');
        }
      }
// Category View....................................
          public function viewCategories(){
              $categories = Category::get();
              // $categories = json_decode(json_encode($categories));
                // dd($categories);
              return view('admin.categories.view_categories')->with(compact('categories'));
          }


}
