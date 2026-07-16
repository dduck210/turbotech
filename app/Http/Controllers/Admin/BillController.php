<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderCancellationService;
use Illuminate\Http\Request;

/**
 * Ported from `Codemoi\Controller\Admin\BillController` (legacy
 * `src/Controller/Admin/BillController.php`).
 */
class BillController extends Controller
{
    /**
     * Forward-only status transition map, applied uniformly to BOTH the
     * manual edit form and the quick-action links — the legacy app's
     * manual form used to accept any status unconditionally, which let a
     * delivered (3) order be "cancelled" (wrongly restocking goods already
     * handed over) and let 4->0->4 cycling re-trigger the stock/coupon
     * restore each time. Self-transitions stay allowed so the form can fix
     * just status_pay without moving status.
     */
    private const ALLOWED_TRANSITIONS = [
        0 => [0, 1, 4],
        1 => [1, 2, 4],
        2 => [2, 3],
        3 => [3],
        4 => [4],
    ];

    public function __construct(private readonly OrderCancellationService $cancellation) {}

    public function index(Request $request)
    {
        $status = (int) $request->input('status', -1);
        $keyword = trim((string) $request->input('keyword', ''));
        $fromDate = $request->input('from_date', '');
        $toDate = $request->input('to_date', '');

        $query = Order::query();
        if ($status > -1) {
            $query->where('status', $status);
        }
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")->orWhere('phone', 'like', "%{$keyword}%");
            });
        }
        if ($fromDate !== '') {
            $query->whereDate('order_date', '>=', $fromDate);
        }
        if ($toDate !== '') {
            $query->whereDate('order_date', '<=', $toDate);
        }

        return view('admin.bill.index', [
            'orders' => $query->orderByDesc('id_bill')->get(),
            'status' => $status,
            'keyword' => $keyword,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function edit(int $id)
    {
        $order = Order::find($id);
        if (! $order) {
            return redirect()->route('admin.orders.index')->with('flash_error', 'Không tìm thấy đơn hàng này.');
        }

        return view('admin.bill.edit', ['order' => $order]);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => ['required', 'integer', 'in:0,1,2,3,4'],
            'status_pay' => ['required', 'integer', 'in:0,1'],
        ]);

        $order = Order::find($id);
        if (! $order) {
            return redirect()->route('admin.orders.index')->with('flash_error', 'Không tìm thấy đơn hàng này.');
        }

        $previousStatus = (int) $order->status;
        $newStatus = (int) $data['status'];

        if (! in_array($newStatus, self::ALLOWED_TRANSITIONS[$previousStatus] ?? [], true)) {
            return redirect()->route('admin.orders.index')->with('flash_error', 'Không thể chuyển đơn hàng sang trạng thái này.');
        }

        $statusPay = $newStatus === Order::STATUS_DELIVERED ? 1 : $data['status_pay'];
        $order->update(['status' => $newStatus, 'status_pay' => $statusPay]);

        if ($newStatus === Order::STATUS_CANCELLED && $previousStatus !== Order::STATUS_CANCELLED) {
            $this->cancellation->cancel($order->fresh());
        }

        return redirect()->route('admin.orders.index')->with('flash_success', 'Cập nhật đơn hàng thành công!');
    }

    public function approve(int $id)
    {
        return $this->transition($id, [Order::STATUS_NEW], Order::STATUS_PROCESSING, 'Đã duyệt đơn hàng thành công!');
    }

    public function ship(int $id)
    {
        return $this->transition($id, [Order::STATUS_PROCESSING], Order::STATUS_SHIPPING, 'Đã chuyển sang đang giao hàng!');
    }

    public function cancel(int $id)
    {
        $order = Order::find($id);
        if ($order && in_array((int) $order->status, [Order::STATUS_NEW, Order::STATUS_PROCESSING], true)) {
            $this->cancellation->cancel($order);

            return redirect()->route('admin.orders.index')->with('flash_success', 'Đã hủy đơn hàng!');
        }

        return redirect()->route('admin.orders.index');
    }

    public function show(int $id)
    {
        $order = Order::find($id);
        if (! $order) {
            return redirect()->route('admin.orders.index')->with('flash_error', 'Không tìm thấy đơn hàng này.');
        }

        return view('admin.bill.show', ['order' => $order]);
    }

    /** Silently no-ops (redirect, no flash) if the order isn't in an eligible from-state — mirrors the legacy inline checks exactly. */
    private function transition(int $id, array $fromStatuses, int $toStatus, string $message)
    {
        $order = Order::find($id);
        if ($order && in_array((int) $order->status, $fromStatuses, true)) {
            $order->update(['status' => $toStatus]);

            return redirect()->route('admin.orders.index')->with('flash_success', $message);
        }

        return redirect()->route('admin.orders.index');
    }
}
