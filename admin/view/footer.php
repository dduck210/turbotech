                </div> <!-- End mx-auto -->
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 py-4 px-6 shrink-0 z-10">
                <div class="text-center text-sm text-slate-500 font-medium">
                    &copy; 2026 Turbotech Admin. Premium Tailwind CSS Redesign.
                </div>
            </footer>
        </div> <!-- End Main Content Wrapper -->
    </div> <!-- End h-screen flex -->
    
    <!-- Shared form-validation engine (same file used by the client storefront) -->
    <script src="../src/js/form-validate.js"></script>

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