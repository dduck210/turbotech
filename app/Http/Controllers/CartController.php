<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

/**
 * Ported from `Codemoi\Controller\CartController` (legacy
 * `src/Controller/CartController.php`).
 */
class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function view()
    {
        return view('cart.view', ['items' => $this->cart->items(), 'total' => $this->cart->total()]);
    }

    /**
     * A negative quantity used to be able to reach Product::decrementStock()'s
     * `stock = stock - ?` arithmetic at checkout, which would then ADD
     * stock back for a negative value — closed here by rejecting anything
     * that isn't >= 0 before it ever reaches the cart (see the legacy
     * CartController::edit()'s fix for the full incident writeup).
     */
    public function edit(Request $request)
    {
        $index = (int) $request->input('code');
        $quantity = (int) $request->input('quantity');
        $items = $this->cart->items();

        if (! isset($items[$index]) || $quantity < 0) {
            return response()->json(['success' => false]);
        }

        $idPro = (int) $items[$index]['id_pro'];
        $product = Product::find($idPro);

        if ($quantity > 0 && $product && ! $product->hasStock($quantity)) {
            $this->cart->update($index, $product->stock);

            return response()->json([
                'success' => false,
                'clampedTo' => $product->stock,
                'message' => 'Chỉ còn '.$product->stock.' sản phẩm trong kho.',
            ]);
        }

        $this->cart->update($index, $quantity);

        return response()->json(['success' => true]);
    }

    /**
     * Never trusts a client-submitted price — always re-fetches the
     * product server-side and uses its post-discount sale price (the same
     * one shown everywhere), mirroring the legacy CartController::add().
     */
    public function add(Request $request)
    {
        $idPro = (int) $request->input('id_pro');
        $quantity = max(1, (int) $request->input('quatity', 1));
        $product = Product::find($idPro);

        if (! $product) {
            return redirect()->route('cart.view');
        }

        $alreadyInCart = 0;
        foreach ($this->cart->items() as $line) {
            if ((int) $line['id_pro'] === $idPro) {
                $alreadyInCart = $line['quantity'];
                break;
            }
        }

        if (! $product->hasStock($alreadyInCart + $quantity)) {
            return redirect()->route('cart.view')->with('flash_error', 'Sản phẩm không đủ số lượng tồn kho!');
        }

        $this->cart->add($idPro, $product->name_pro, $product->img_pro, $product->discounted_price, $quantity);

        return redirect()->route('cart.view');
    }

    public function remove(Request $request)
    {
        $index = $request->query('idcart');
        $this->cart->remove($index !== null ? (int) $index : null);

        return redirect()->route('cart.view');
    }
}
