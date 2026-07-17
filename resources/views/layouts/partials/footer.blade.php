<footer class="mt-20 border-t border-ink-200 bg-ink-950 text-ink-300">
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-4 py-14 sm:px-6 md:grid-cols-4 lg:px-8">
        <div>
            <a href="{{ route('home') }}" class="font-heading text-xl font-bold text-white">
                Turbo<span class="text-brand-400">tech</span>
            </a>
            <p class="mt-3 max-w-xs text-sm text-ink-400">
                Laptop gaming &amp; PC hiệu năng cao chính hãng — cấu hình mạnh mẽ, giá cạnh tranh, bảo hành chính hãng 12 tháng.
            </p>
        </div>

        <div>
            <h3 class="eyebrow text-ink-400">Mua sắm</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                <li><a href="{{ route('product.index') }}" class="transition-colors hover:text-brand-400">Tất cả sản phẩm</a></li>
                <li><a href="{{ route('cart.view') }}" class="transition-colors hover:text-brand-400">Giỏ hàng</a></li>
                <li><a href="{{ route('account.index') }}" class="transition-colors hover:text-brand-400">Tài khoản của tôi</a></li>
            </ul>
        </div>

        <div>
            <h3 class="eyebrow text-ink-400">Hỗ trợ</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                <li><a href="{{ route('introduce') }}" class="transition-colors hover:text-brand-400">Giới thiệu</a></li>
                <li><a href="{{ route('contact') }}" class="transition-colors hover:text-brand-400">Liên hệ</a></li>
                <li><a href="{{ route('question.index') }}" class="transition-colors hover:text-brand-400">Hỏi đáp</a></li>
            </ul>
        </div>

        <div>
            <h3 class="eyebrow text-ink-400">Liên hệ</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                <li class="flex items-start gap-2"><i class="fas fa-map-marker-alt mt-1 text-brand-500"></i> Việt Nam</li>
                <li class="flex items-start gap-2"><i class="fas fa-envelope mt-1 text-brand-500"></i> support@turbotech.vn</li>
            </ul>
        </div>
    </div>

    <div class="border-t border-ink-800 py-6 text-center text-xs text-ink-500">
        &copy; {{ date('Y') }} Turbotech. Đã đăng ký bản quyền.
    </div>
</footer>
