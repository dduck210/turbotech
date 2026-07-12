/**
 * Cascading Tỉnh/Thành phố -> Xã/Phường address picker (vanilla JS).
 * Data is the current 2-tier administrative structure (34 provinces, no
 * district level) fetched from provinces.open-api.vn and saved locally at
 * src/data/vietnam-locations.json so the page doesn't depend on that API
 * at runtime.
 *
 * Usage: a <select data-address-province>, a <select data-address-ward>,
 * and (optionally) an input/textarea data-address-detail, all inside the
 * same <form>, get wired up automatically on DOMContentLoaded.
 *
 * Pre-filling an existing address: put the previously-stored combined
 * string (whatever is in the `address` column) on the province select's
 * data-existing-address attribute. Addresses built by this picker are
 * always "{detail}, {ward name}, {province name}", so on load this script
 * splits on ", " and tries to match the last two parts against the loaded
 * province/ward names. If both match, the dropdowns are pre-selected and
 * the detail field gets just the leading part. If not — e.g. an address
 * entered before this picker existed, or in some other free-text shape —
 * the dropdowns are left unselected and the whole original string is
 * dropped into the detail field instead, so nothing is silently lost.
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

    function populateWards(wardSelect, wards, selectedName) {
        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
        wards.forEach(function (w) {
            var opt = document.createElement('option');
            opt.value = w.name;
            opt.textContent = w.name;
            if (selectedName && w.name === selectedName) {
                opt.selected = true;
            }
            wardSelect.appendChild(opt);
        });
        wardSelect.disabled = wards.length === 0;
    }

    /**
     * Try to split a stored "{detail}, {ward}, {province}" string against
     * the known province/ward names. Returns null when it doesn't match
     * (older or otherwise free-form address), in which case the caller
     * falls back to putting the whole string in the detail field.
     */
    function parseExistingAddress(address, provinces) {
        var parts = address.split(',').map(function (s) { return s.trim(); });
        if (parts.length < 3) return null;

        var provinceName = parts[parts.length - 1];
        var wardName = parts[parts.length - 2];
        var detail = parts.slice(0, parts.length - 2).join(', ');

        var province = provinces.find(function (p) { return p.name === provinceName; });
        if (!province) return null;
        var ward = province.wards.find(function (w) { return w.name === wardName; });
        if (!ward) return null;

        return { province: provinceName, ward: wardName, detail: detail };
    }

    function initPicker(provinceSelect) {
        var form = provinceSelect.closest('form') || document;
        var wardSelect = form.querySelector('[data-address-ward]');
        var detailField = form.querySelector('[data-address-detail]');
        if (!wardSelect) return;

        var existingAddress = provinceSelect.dataset.existingAddress || '';

        loadLocations().then(function (provinces) {
            var parsed = existingAddress ? parseExistingAddress(existingAddress, provinces) : null;

            provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
            provinces.forEach(function (p) {
                var opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                if (parsed && p.name === parsed.province) {
                    opt.selected = true;
                }
                provinceSelect.appendChild(opt);
            });

            if (parsed) {
                var province = provinces.find(function (p) { return p.name === parsed.province; });
                populateWards(wardSelect, province.wards, parsed.ward);
                if (detailField) detailField.value = parsed.detail;
            } else {
                wardSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố trước</option>';
                wardSelect.disabled = true;
                // Address existed but didn't match the picker's format (e.g.
                // entered before this feature existed) — keep it visible
                // rather than silently discarding it.
                if (detailField && existingAddress) detailField.value = existingAddress;
            }

            provinceSelect.addEventListener('change', function () {
                var name = provinceSelect.value;
                var province = provinces.find(function (p) { return p.name === name; });
                populateWards(wardSelect, province ? province.wards : []);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-address-province]').forEach(initPicker);
    });
})();
