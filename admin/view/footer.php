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

        // Mobile sidebar toggle logic
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobile-sidebar-toggle');
            const sidebar = document.getElementById('mobile-sidebar-nav');
            if (btn && sidebar) {
                btn.addEventListener('click', () => {
                    sidebar.classList.toggle('hidden');
                    sidebar.classList.toggle('absolute');
                    sidebar.classList.toggle('h-full');
                    sidebar.classList.toggle('z-50');
                });
            }
        });
    </script>
</body>
</html>