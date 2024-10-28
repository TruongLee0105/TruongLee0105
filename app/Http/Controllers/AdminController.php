<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Category_products;
use App\Models\Category_service;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function admin()
    {
        return view('admin.admin');
    }

//     ||       ============================================================
//     ||                              PRODUCT 
//     \/       ============================================================
    public function productadmin()
    {
        $newestProducts = Products::orderBy('id', 'desc')->paginate(6);
        $category_products = Category_products::orderBy('name', 'asc')->get(); // Lấy danh sách các danh mục
        return view('admin.productadmin', compact('newestProducts', 'category_products'));
    }
    public function search_admin(Request $request)
    {
        $query = $request->input('query');
        $products = Products::where('name', 'LIKE', "%$query%")->paginate(6);
        return view('admin.search_admin', compact('products', 'query'));
    }

    public function add_product(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:category_products,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048', // Kiểm tra hình ảnh
        ]);

        // Lưu sản phẩm vào cơ sở dữ liệu
        $data = $request->only(['name', 'description', 'price', 'category_id']);

        // Xử lý lưu hình ảnh
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->move(public_path('upload'), $imageName);
            $data['image'] = $imageName;
        }

        Products::create($data);

        return redirect()->route('productadmin')->with('success', 'Sản phẩm đã được thêm thành công.');
    }


    public function delete_product($id)
    {
        $product = Products::find($id);
    
        if ($product) {
            // Xóa tệp hình ảnh nếu nó tồn tại
            if ($product->image && file_exists(public_path('upload/' . $product->image))) {
                unlink(public_path('upload/' . $product->image)); 
            }
    
            // Xóa bản ghi sản phẩm khỏi cơ sở dữ liệu
            $product->delete();
    
            return redirect()->route('productadmin')->with('success', 'Sản phẩm đã được xóa thành công.');
        } else {
            return redirect()->route('productadmin')->with('error', 'Không tìm thấy sản phẩm.');
        }
    }
    
    public function edit_product($id)
    {
        $product = Products::find($id);
        if ($product) {
            $category_products = Category_products::orderBy('id', 'asc')->get(); // Lấy danh sách các danh mục
            return view('admin.updateproduct', compact('product', 'category_products'));
        } else {
            return redirect()->route('productadmin')->with('error', 'Không tìm thấy sản phẩm.');
        }
    }

    public function update_product(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'category_id' => 'required|exists:category_products,id',
        'image' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048', // Kiểm tra hình ảnh
    ]);

    $product = Products::find($id);
    if ($product) {
        $data = $request->only(['name', 'description', 'price', 'category_id']);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($product->image && file_exists(public_path('upload/' . $product->image))) {
                unlink(public_path('upload/' . $product->image));
            }

            // Lưu ảnh mới
            $image = $request->file('image');
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->move(public_path('upload'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('productadmin')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    } else {
        return redirect()->route('productadmin')->with('error', 'Không tìm thấy sản phẩm.');
    }
}



//     ||       ============================================================
//     ||                              CATEGORY 
//     \/       ============================================================


    public function categoryadmin()
    {
        $allCategories = Category_products::withCount('products')->orderBy('id', 'asc')->get();
        return view('admin.categoryadmin', compact('allCategories'));
    }
    public function add_category(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        // Lưu sản phẩm vào cơ sở dữ liệu
        $data = $request->only(['name']);

        Category_products::create($data);

        return redirect()->route('categoryadmin')->with('success', 'Danh mục thêm thành công.');
    }
    public function delete_category($id)
    {
        $allCategories = Category_products::find($id);
    
        if ($allCategories) {
            // Kiểm tra xem danh mục có chứa sản phẩm nào không
            if ($allCategories->products()->count() > 0) {
                return redirect()->route('categoryadmin')->with('error', 'Danh mục này vẫn còn sản phẩm, không thể xóa.');
            } else {
                $allCategories->delete();
                return redirect()->route('categoryadmin')->with('success', 'Danh mục đã được xóa thành công.');
            }
        } else {
            return redirect()->route('categoryadmin')->with('error', 'Không tìm thấy danh mục.');
        }
    }
    
    public function edit_category($id)
    {
        $allCategories = Category_products::find($id);
        
        if ($allCategories) {
            return view('admin.updatecategory', compact('allCategories'));
        } else {
            return redirect()->route('categoryadmin')->with('error', 'Không tìm thấy sản phẩm.');
        }
    }

    public function update_category(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
           
        ]);
    
        $category = Category_products::find($id);
        if ($category) {
            $data = $request->only(['name', 'status']);
           
    
            $category->update($data);
    
            return redirect()->route('categoryadmin')->with('success', 'Danh mục đã được cập nhật thành công.');
        } else {
            return redirect()->route('categoryadmin')->with('error', 'Không tìm thấy danh mục.');
        }
    }


// //     ||       ============================================================
// //     ||                              USER 
// //     \/       ============================================================


    public function useradmin()
    {
        $allUser = User::orderBy('id','asc')->get();
        return view('admin.useradmin', compact('allUser'));
    }
    public function add_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        User::create([
            'name' => $request->name,
            'phone' => 0, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => 0, 
            'role' => 1, 
        ]);

        return redirect()->route('useradmin')->with('success', 'Người dùng thêm thành công.');
    }
    public function delete_user($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('useradmin')->with('error', 'Không tìm thấy người dùng.');
        }

        // Kiểm tra nếu người dùng hiện tại đang cố gắng tự xóa chính mình
        if (Auth::id() == $user->id) {
            return redirect()->route('useradmin')->with('error', 'Bạn không thể tự xóa tài khoản của chính mình.');
        }

        try {
            // Kiểm tra sự tồn tại của giỏ hàng
            if ($user->cart) {
                // Xóa tất cả các mục trong giỏ hàng của người dùng
                foreach ($user->cart->items as $item) {
                    $item->delete();
                }
                // Xóa giỏ hàng của người dùng
                $user->cart()->delete();
            }

            // Xóa người dùng
            $user->delete();

            return redirect()->route('useradmin')->with('success', 'Người dùng đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('useradmin')->with('error', 'Đã xảy ra lỗi khi xóa người dùng: ' . $e->getMessage());
        }
    }
    public function edit_user($id)
    {
        $user = User::find($id);
        
        if ($user) {
            return view('admin.updateuser', compact('user'));
        } else {
            return redirect()->route('useradmin')->with('error', 'Không tìm thấy người dùng.');
        }
    }

    public function update_user(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|integer|in:0,1',
           
        ]);
    
        $user = User::find($id);
        if ($user) {
            $data = $request->only(['name','role']);
           
    
            $user->update($data);
    
            return redirect()->route('useradmin')->with('success', 'Người dùng đã được cập nhật thành công.');
        } else {
            return redirect()->route('useradmin')->with('error', 'Không tìm thấy người dùng.');
        }
    }

// //     ||       ============================================================
// //     ||                              SERVICE CATEGORY 
// //     \/       ============================================================

    public function category_sv_admin()
    {
        $allService = Category_service::all();
        return view('admin.category_sv_admin', compact('allService'));
    }
    public function add_category_sv(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        // Lưu sản phẩm vào cơ sở dữ liệu
        $data = $request->only(['name']);

        Category_service::create($data);

        return redirect()->route('category_sv_admin')->with('success', 'Danh mục thêm thành công.');
    }
    public function delete_category_sv($id)
    {
        $allService = Category_service::find($id);
    
        if ($allService) {
            // Kiểm tra xem danh mục có chứa sản phẩm nào không
            if ($allService->products()->count() > 0) {
                return redirect()->route('category_sv_admin')->with('error', 'Danh mục này vẫn còn sản phẩm, không thể xóa.');
            } else {
                $allService->delete();
                return redirect()->route('category_sv_admin')->with('success', 'Danh mục đã được xóa thành công.');
            }
        } else {
            return redirect()->route('category_sv_admin')->with('error', 'Không tìm thấy danh mục.');
        }
    }
    
    public function edit_category_sv($id)
    {
        $allService = Category_service::find($id);
        
        if ($allService) {
            return view('admin.update_category_sv', compact('allService'));
        } else {
            return redirect()->route('category_sv_admin')->with('error', 'Không tìm thấy sản phẩm.');
        }
    }

    public function update_category_sv(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
           
        ]);
    
        $category_sv = Category_service::find($id);
        if ($category_sv) {
            $data = $request->only(['name', 'status']);
           
    
            $category_sv->update($data);
    
            return redirect()->route('category_sv_admin')->with('success', 'Danh mục đã được cập nhật thành công.');
        } else {
            return redirect()->route('category_sv_admin')->with('error', 'Không tìm thấy danh mục.');
        }
    }
// //     ||       ============================================================
// //     ||                              ORDER
// //     \/       ============================================================

    public function order()
    {
        // Lấy danh sách đơn hàng phân trang, ví dụ: 10 đơn hàng mỗi trang
        $orders = Order::orderBy('id', 'desc')->paginate(10);
        
        $statuses = [
            1 => 'Chờ xác nhận',
            2 => 'Đang chuẩn bị hàng',
            3 => 'Đang được giao',
            4 => 'Đơn hàng đã hoàn thành'
        ];
    
        return view('admin.order', compact('orders', 'statuses'));
    }
    
    
    public function updateStatus(Request $request, $orderId)
    {
        // Tìm đơn hàng theo ID
        $order = Order::findOrFail($orderId);
    
        // Increment status, ensure it doesn't exceed 4
        if ($order->status < 4) {
            $order->status++;
            $order->save();
        }
    
        // Lấy danh sách đơn hàng và các trạng thái hiện có
        $orders = Order::orderBy('id', 'asc')->get();
        $statuses = [
            1 => 'Chờ xác nhận',
            2 => 'Đang chuẩn bị hàng',
            3 => 'Đang được giao',
            4 => 'Đơn hàng đã hoàn thành'
        ];
    
        // Trả về cùng một view với danh sách đơn hàng và trạng thái mới
        return view('admin.order', compact('orders', 'statuses'))->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }
    


 }


