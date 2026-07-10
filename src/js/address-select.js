/**
 * Cascading Tỉnh/Thành phố -> Xã/Phường address picker (vanilla JS).
 * Data is the current 2-tier administrative structure (34 provinces, no
 * district level) fetched from provinces.open-api.vn and saved locally at
 * src/data/vietnam-locations.json so the page doesn't depend on that API
 * at runtime.
 *
 * Usage: a <select data-address-province> and a <select data-address-ward>
 * anywhere on the page get wired up automatically on DOMContentLoaded. If
 * the ward select has a data-selected-code attribute (e.g. when
 * re-rendering a form with a previously chosen value), that ward is
 * restored once its province's ward list loads.
 */
(function () {
    'use strict';

    var locationsPromise = null;

    function loadLocations() {
        if (!locationsPromise) {
            locationsPromise = fetch('src/data/vietnam-locations.json').then(function (r) {
                return r.json();
            });
        }
        return locationsPromise;
    }

    function populateWards(wardSelect, wards, selectedCode) {
        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
        wards.forEach(function (w) {
            var opt = document.createElement('option');
            opt.value = w.name;
            opt.textContent = w.name;
            opt.dataset.code = w.code;
            if (selectedCode && String(w.code) === String(selectedCode)) {
                opt.selected = true;
            }
            wardSelect.appendChild(opt);
        });
        wardSelect.disabled = wards.length === 0;
    }

    function initPicker(provinceSelect) {
        var form = provinceSelect.closest('form') || document;
        var wardSelect = form.querySelector('[data-address-ward]');
        if (!wardSelect) return;

        var initialProvinceCode = provinceSelect.dataset.selectedCode || '';
        var initialWardCode = wardSelect.dataset.selectedCode || '';

        loadLocations().then(function (provinces) {
            provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
            provinces.forEach(function (p) {
                var opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                opt.dataset.code = p.code;
                if (initialProvinceCode && String(p.code) === String(initialProvinceCode)) {
                    opt.selected = true;
                }
                provinceSelect.appendChild(opt);
            });

            var selected = provinces.find(function (p) {
                return String(p.code) === String(initialProvinceCode);
            });
            if (selected) {
                populateWards(wardSelect, selected.wards, initialWardCode);
            } else {
                wardSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố trước</option>';
                wardSelect.disabled = true;
            }

            provinceSelect.addEventListener('change', function () {
                var code = provinceSelect.selectedOptions[0] ? provinceSelect.selectedOptions[0].dataset.code : '';
                var province = provinces.find(function (p) {
                    return String(p.code) === String(code);
                });
                populateWards(wardSelect, province ? province.wards : []);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-address-province]').forEach(initPicker);
    });
})();
