                </div> <!-- End mx-auto -->
                </main>

                <!-- Footer -->
                <footer class="bg-white border-t border-ink-200 py-4 px-6 shrink-0 z-10">
                    <div class="text-center text-sm text-ink-500 font-medium">
                        &copy; 2026 Turbotech Admin.
                    </div>
                </footer>
                </div> <!-- End Main Content Wrapper -->
                </div> <!-- End h-screen flex -->

                <!-- Custom confirm dialog (replaces the native browser confirm() popup) —
         logic lives in assets/js/confirm-dialog.js; markup stays here so its
         classes are seen by Tailwind's content scan. -->
                <div id="confirm-dialog-overlay" class="hidden fixed inset-0 z-50 bg-ink-900/50 p-4">
                    <div class="flex h-full w-full items-center justify-center">
                        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                                </div>
                                <h3 class="text-base font-semibold text-ink-900">Xác nhận</h3>
                            </div>
                            <p id="confirm-dialog-message" class="mb-6 text-sm text-ink-600"></p>
                            <div class="flex justify-end gap-3">
                                <button type="button" id="confirm-dialog-cancel"
                                    class="rounded-lg border border-ink-200 bg-white px-4 py-2 text-sm font-semibold text-ink-900 transition-colors hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500">Hủy</button>
                                <button type="button" id="confirm-dialog-ok"
                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Xác
                                    nhận</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shared form-validation engine (same file used by the client storefront) -->
                <script src="../assets/js/form-validate.js"></script>
                <script src="../assets/js/confirm-dialog.js"></script>

                <!-- Scripts for interactivity (DataTables, Dropdowns, etc.) -->
                <script>
                    // Toggle submenus in sidebar (smooth grid-template-rows animation)
                    function toggleSubmenu(id) {
                        const el = document.getElementById(id);
                        const chevron = document.getElementById(id + '-chevron');
                        if (!el) return;
                        el.classList.toggle('grid-rows-[0fr]');
                        el.classList.toggle('grid-rows-[1fr]');
                        if (chevron) chevron.classList.toggle('rotate-180');
                    }

                    // Mobile sidebar toggle logic (sidebar overlays as a fixed panel on
                    // mobile via nav.php's "fixed inset-y-0 left-0 md:relative" classes;
                    // this only needs to flip visibility + the backdrop, not position).
                    document.addEventListener('DOMContentLoaded', () => {
                        const btn = document.getElementById('mobile-sidebar-toggle');
                        const sidebar = document.getElementById('mobile-sidebar-nav');
                        const backdrop = document.getElementById('mobile-sidebar-backdrop');
                        if (!btn || !sidebar) return;

                        const closeSidebar = () => {
                            sidebar.classList.add('hidden');
                            if (backdrop) backdrop.classList.add('hidden');
                        };
                        const toggleSidebar = () => {
                            sidebar.classList.toggle('hidden');
                            if (backdrop) backdrop.classList.toggle('hidden');
                        };

                        btn.addEventListener('click', toggleSidebar);
                        if (backdrop) backdrop.addEventListener('click', closeSidebar);
                    });
                </script>
                </body>

                </html>