<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Model\Order;

/**
 * Bill/order management. Ported from `public/admin/index.php` cases
 * `list_bill`/`edit_bill`/`update_bill`/`approve_bill`/`ship_bill`/
 * `cancel_bill`/`billdetail`.
 *
 * Domain naming caveat preserved: the `cart` table holds ORDER LINE ITEMS
 * (`Model\Order::items()`), not an active shopping cart — the live cart is
 * `$_SESSION`. Not renamed.
 */
class BillController extends AdminController
{
    /**
     * Same forward-only workflow the guarded quick actions (approve/ship/
     * cancel) already enforce, applied to the manual edit form too — that
     * form used to accept ANY status unconditionally, which let a
     * delivered (3) order be "cancelled," wrongly restocking goods already
     * handed to the customer, and let cycling 4->0->4 re-trigger the
     * stock/coupon restore every time. Each status also allows staying put
     * (e.g. 1 -> 1) so the form can still be used to fix just `status_pay`
     * without moving the order status at all.
     */
    private const ALLOWED_TRANSITIONS = [
        0 => [0, 1, 4],
        1 => [1, 2, 4],
        2 => [2, 3],
        3 => [3],
        4 => [4],
    ];

    public function list(): void
    {
        $this->requireAdmin();

        $status = -1;
        $keyword = '';
        $from_date = '';
        $to_date = '';

        if (isset($_POST['btn_filter'])) {
            if (isset($_POST['status'])) {
                $status = (int) $_POST['status'];
            }
            if (isset($_POST['keyword'])) {
                $keyword = trim($_POST['keyword']);
            }
            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
            }
            if (isset($_POST['to_date'])) {
                $to_date = $_POST['to_date'];
            }
        }

        $this->render('list_bill', [
            'listbill' => Order::allAdmin($status, $keyword, $from_date, $to_date),
            'status' => $status,
            'keyword' => $keyword,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $one_bill = (isset($_GET['idbill']) && $_GET['idbill'] > 0)
            ? Order::one((int) $_GET['idbill'])
            : false;

        if (!is_array($one_bill)) {
            $_SESSION['flash_error'] = 'Không tìm thấy đơn hàng này.';
            $this->redirect('index.php?act=list_bill');
        }

        $this->render('update_bill', ['one_bill' => $one_bill]);
    }

    /** Old code had no admin-session guard here — see CategoryController::update() for why. */
    public function update(): void
    {
        $this->requireAdmin();

        if (isset($_POST['btn_update']) && $_POST['btn_update']) {
            $id_bill = (int) $_POST['id_bill'];
            $status = $_POST['status'] ?? '';
            $status_pay = $_POST['status_pay'] ?? '';

            if (!in_array($status, ['0', '1', '2', '3', '4'], true) || !in_array($status_pay, ['0', '1'], true)) {
                $_SESSION['flash_error'] = 'Trạng thái không hợp lệ.';
                $this->redirect('index.php?act=list_bill');
            }

            $previousBill = Order::one($id_bill);
            if (!$previousBill) {
                $_SESSION['flash_error'] = 'Không tìm thấy đơn hàng này.';
                $this->redirect('index.php?act=list_bill');
            }

            $previousStatus = (int) $previousBill['status'];
            $newStatus = (int) $status;

            if (!in_array($newStatus, self::ALLOWED_TRANSITIONS[$previousStatus] ?? [], true)) {
                $_SESSION['flash_error'] = 'Không thể chuyển đơn hàng sang trạng thái này.';
                $this->redirect('index.php?act=list_bill');
            }

            if ($status == 3) {
                $status_pay = 1;
            }

            Order::updateStatus($id_bill, $status, $status_pay);

            if ($newStatus === 4 && $previousStatus !== 4) {
                Order::restockAndRefundCoupon($id_bill);
            }

            $_SESSION['flash_success'] = 'Cập nhật đơn hàng thành công!';
        }

        $this->redirect('index.php?act=list_bill');
    }

    /** Same missing-guard gap as elsewhere — fixed via requireAdmin(). */
    public function approve(): void
    {
        $this->requireAdmin();
        $this->transition([0], '1', 'Đã duyệt đơn hàng thành công!');
    }

    public function ship(): void
    {
        $this->requireAdmin();
        $this->transition([1], '2', 'Đã chuyển sang đang giao hàng!');
    }

    public function cancel(): void
    {
        $this->requireAdmin();
        $this->transition([0, 1], '4', 'Đã hủy đơn hàng!');
    }

    public function detail(): void
    {
        $this->requireAdmin();

        $one_bill = (isset($_GET['idbill']) && $_GET['idbill'] > 0)
            ? Order::one((int) $_GET['idbill'])
            : false;

        if (!is_array($one_bill)) {
            $_SESSION['flash_error'] = 'Không tìm thấy đơn hàng này.';
            $this->redirect('index.php?act=list_bill');
        }

        $this->render('billdetail', ['one_bill' => $one_bill]);
    }

    /**
     * Shared status-transition guard: only moves `$fromStatuses` -> `$toStatus`,
     * silently no-ops (still redirects, no flash) for any other current state —
     * mirrors the old inline `if ($bill && $bill['status'] == N)` checks exactly.
     */
    private function transition(array $fromStatuses, string $toStatus, string $message): void
    {
        if (isset($_GET['idbill']) && $_GET['idbill'] > 0) {
            $idbill = (int) $_GET['idbill'];
            $bill = Order::one($idbill);
            if ($bill && in_array((int) $bill['status'], $fromStatuses, true)) {
                Order::updateStatus($idbill, $toStatus, $bill['status_pay']);

                if ($toStatus === '4') {
                    Order::restockAndRefundCoupon($idbill);
                }

                $_SESSION['flash_success'] = $message;
            }
        }

        $this->redirect('index.php?act=list_bill');
    }
}
