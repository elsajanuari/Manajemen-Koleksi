<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active');

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);

        return view('sales.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('sales.show', compact('product'));
    }

    public function cart()
    {
        // For now, session-based cart
        $cart = session()->get('cart', []);
        return view('sales.cart', compact('cart'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);

        foreach ($request->quantities as $id => $quantity) {
            if ($quantity > 0) {
                $cart[$id]['quantity'] = $quantity;
            } else {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('sales.index')->with('error', 'Cart is empty!');
        }

        return view('sales.checkout', compact('cart'));
    }

    public function processCheckout(Request $request)
    {
        // Validation and processing will be added later
        // For now, just redirect
        return redirect()->route('sales.index')->with('success', 'Order placed successfully!');
    }
}
