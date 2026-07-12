/**
 * Shared client-side form validation (vanilla JS, no dependencies).
 * Works for both the client storefront (ink/brand theme) and the admin
 * panel (slate/brand theme) since error styling relies only on a
 * `.is-invalid` marker class defined in each theme's compiled CSS, not on
 * competing Tailwind utility classes (see admin-tailwind-input.css comment
 * for why plain utility overrides are unreliable here).
 *
 * Usage: add `data-validate` to a <form>, then `data-rules="required|email"`
 * (pipe-separated) to any field that needs checking. Supported rules:
 *   required            - not empty after trim
 *   email               - basic email shape
 *   phone               - Vietnamese phone number (9-11 digits, optional +84/0 prefix)
 *   min:N               - string length >= N
 *   max:N               - string length <= N
 *   number               - numeric value
 *   minval:N            - numeric value >= N
 *   match:otherFieldName - value must equal another field's value (e.g. confirm password)
 *
 * Custom error text: data-msg-required="...", data-msg-email="...", etc.
 * Falls back to a Vietnamese default message per rule when not provided.
 */
(function () {
    'use strict';

    var DEFAULT_MESSAGES = {
        required: 'Vui lòng nhập trường này',
        email: 'Địa chỉ email không hợp lệ',
        phone: 'Số điện thoại không hợp lệ',
        min: 'Cần ít nhất {0} ký tự',
        max: 'Không vượt quá {0} ký tự',
        number: 'Vui lòng nhập một số hợp lệ',
        minval: 'Giá trị phải lớn hơn hoặc bằng {0}',
        match: 'Giá trị không khớp'
    };

    function formatMessage(template, arg) {
        return template.replace('{0}', arg);
    }

    function getErrorEl(field) {
        var next = field.nextElementSibling;
        if (next && next.classList.contains('field-error')) {
            return next;
        }
        var el = document.createElement('p');
        el.className = 'field-error';
        field.insertAdjacentElement('afterend', el);
        return el;
    }

    function setInvalid(field, message) {
        field.classList.add('is-invalid');
        field.setAttribute('aria-invalid', 'true');
        var errorEl = getErrorEl(field);
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }

    function setValid(field) {
        field.classList.remove('is-invalid');
        field.removeAttribute('aria-invalid');
        var next = field.nextElementSibling;
        if (next && next.classList.contains('field-error')) {
            next.textContent = '';
            next.classList.add('hidden');
        }
    }

    function customMessage(field, rule) {
        var attr = field.getAttribute('data-msg-' + rule);
        return attr || null;
    }

    function validateField(field) {
        var rulesAttr = field.getAttribute('data-rules');
        if (!rulesAttr) return true;

        var value = field.value.trim();
        var rules = rulesAttr.split('|');

        for (var i = 0; i < rules.length; i++) {
            var parts = rules[i].split(':');
            var rule = parts[0];
            var arg = parts[1];

            if (rule === 'required') {
                if (value === '') {
                    setInvalid(field, customMessage(field, 'required') || DEFAULT_MESSAGES.required);
                    return false;
                }
                continue;
            }

            // Remaining rules only apply when the field has a value (an
            // empty optional field shouldn't fail format checks).
            if (value === '') continue;

            if (rule === 'email') {
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    setInvalid(field, customMessage(field, 'email') || DEFAULT_MESSAGES.email);
                    return false;
                }
            } else if (rule === 'phone') {
                if (!/^(\+?84|0)\d{9,10}$/.test(value.replace(/[\s.-]/g, ''))) {
                    setInvalid(field, customMessage(field, 'phone') || DEFAULT_MESSAGES.phone);
                    return false;
                }
            } else if (rule === 'min') {
                if (value.length < parseInt(arg, 10)) {
                    setInvalid(field, customMessage(field, 'min') || formatMessage(DEFAULT_MESSAGES.min, arg));
                    return false;
                }
            } else if (rule === 'max') {
                if (value.length > parseInt(arg, 10)) {
                    setInvalid(field, customMessage(field, 'max') || formatMessage(DEFAULT_MESSAGES.max, arg));
                    return false;
                }
            } else if (rule === 'number') {
                if (isNaN(Number(value))) {
                    setInvalid(field, customMessage(field, 'number') || DEFAULT_MESSAGES.number);
                    return false;
                }
            } else if (rule === 'minval') {
                if (isNaN(Number(value)) || Number(value) < parseFloat(arg)) {
                    setInvalid(field, customMessage(field, 'minval') || formatMessage(DEFAULT_MESSAGES.minval, arg));
                    return false;
                }
            } else if (rule === 'match') {
                var otherField = field.form.querySelector('[name="' + arg + '"]');
                if (otherField && value !== otherField.value.trim()) {
                    setInvalid(field, customMessage(field, 'match') || DEFAULT_MESSAGES.match);
                    return false;
                }
            }
        }

        setValid(field);
        return true;
    }

    function initForm(form) {
        var fields = form.querySelectorAll('[data-rules]');

        fields.forEach(function (field) {
            field.addEventListener('blur', function () {
                validateField(field);
            });
            field.addEventListener('input', function () {
                if (field.classList.contains('is-invalid')) {
                    validateField(field);
                }
            });
        });

        form.addEventListener('submit', function (e) {
            var firstInvalid = null;
            fields.forEach(function (field) {
                var ok = validateField(field);
                if (!ok && !firstInvalid) {
                    firstInvalid = field;
                }
            });
            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[data-validate]').forEach(initForm);
    });
})();
