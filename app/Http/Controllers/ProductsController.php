<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Products;
use App\Models\Category_products;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function products()
    {
        $allProducts = Products::with('category')->orderBy('name', 'asc')->paginate(9);
        $allCategories = Category_products::orderBy('name', 'asc')->get();
        return view('products', compact('allProducts', 'allCategories'));
    }

    public function getProductsByCategory($category_id)
    {
        $allProducts = Products::where('category_id', $category_id)->with('category')->orderBy('name', 'asc')->paginate(9);
        $allCategories = Category_products::orderBy('name', 'asc')->get();
        $currentCategory = Category_products::find($category_id);
        $categoryName = $currentCategory ? $currentCategory->name : 'Danh mục không tồn tại';
        return view('products', compact('allProducts', 'allCategories', 'currentCategory', 'categoryName'));
    }

    public function detail($id)
    {
        $item = Products::with('category')->find($id);
        $relatedProducts = Products::where('category_id', $item->category_id)->where('id', '!=', $id)->limit(4)->get();
        return view('detail', compact('item', 'relatedProducts'));
    }
    public function comment(Request $request, $productId)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
            'author' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
    
        $comment = new Comment();
        $comment->product_id = $productId;
        $comment->user_id = auth()->id() ?: null; // Lưu user_id nếu đã đăng nhập
        $comment->comment_text = $request->input('comment');
        $comment->save();
    
        return redirect()->back()->with('success', 'Bình luận đã được thêm thành công!');
    }
    



}