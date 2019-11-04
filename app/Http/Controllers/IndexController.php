<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

class IndexController extends Controller
{
    public function Index(){
      // In Assending Order is ( by default ).........
        $productsAll = Product::get();
      // In Assending Order  .........
      $productsAll = Product::Orderby('id', 'DESC')->get();
      // In Random Order.........
        $productsAll = Product::inRandomOrder()->get();
      // Get All Categories and sub Categories
      $categories = Category::with('categories')->where(['parent_id'=>0])->get();
      // dd($categories);
      // $category_menu = "";
      // foreach ($categories as $cat) {
      //   // echo $cat->name; echo "</br>";
      //   $category_menu .= "<div class='panel-heading'>
      //           <h4 class='panel-title'>
      //             <a data-toggle='collapse' data-parent='accordian'
      //             href='#".$cat->id."'>
      //               <span class='badge pull-right'><i class='fa fa-plus'></i></span>
      //               ".$cat->name." </a>
      //           </h4>
      //         </div>
      //       <div id='".$cat->id."' class='panel-collapse collapse'>
			// 				<div class='panel-body'>
			// 					<ul>";
      //             $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
      //               foreach ($sub_categories as $subcat) {
      //                 $category_menu .="<li><a href='".$subcat->url."'>".$subcat->name."</a></li>";
      //               }
      //               $category_menu .=" </ul>
			// 				</div>
			// 			</div>
      //      ";
      // }
      // // die;
      return view('index')->with(compact('productsAll', 'category_menu', 'categories'));
    }
}
