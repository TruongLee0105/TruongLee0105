<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price','image','sold' ,'view', 'category_id'];

    public function scopeNewProducts($query, $limit)
    {
        return $query->orderBy('id', 'desc')->limit($limit)->with(['category']);
    }

    public function scopeBestsellerProducts($query, $limit)
    {
        return $query->where('sold', '>', 0)->orderBy('id', 'desc')->limit($limit)->with(['category']);
    }

    public function scopeInStockProducts($query, $limit)
    {
        return $query->where('sold', '<', 100)->orderBy('id', 'desc')->limit($limit)->with(['category']);
    }

    public function category()
    {
        return $this->belongsTo(Category_products::class);
    }

    public static function createProduct($data)
    {
        return self::create($data);
    }

    public static function getProductById($id)
    {
        return self::find($id);
    }

    public static function updateProduct($id, $data)
    {
        $product = self::find($id);
        if ($product) {
            $product->update($data);
            return $product;
        }
        return null;
    }
    public function scopeAllProducts($query)
    {
        return $query->with(['category']);
    }
    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product')
                    ->withPivot('quantity') // Lấy thêm thông tin quantity từ bảng pivot
                    ->withTimestamps(); // Lưu thời gian tạo/cập nhật trong bảng pivot
    }
    public function comments()
{
    return $this->hasMany(Comment::class);
}
}