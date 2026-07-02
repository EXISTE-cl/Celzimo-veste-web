document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    if (!form) return;

    const emailInput = document.getElementById('reg-email');
    const emailConfirmInput = document.getElementById('reg-email-confirm');
    const emailConfirmError = document.getElementById('email-confirm-error');
    
    const phoneInput = document.getElementById('reg-phone');
    const phoneError = document.getElementById('phone-error');

    const pwdInput = document.getElementById('reg-password');
    const confirmInput = document.getElementById('reg-confirm');
    const togglePwdBtn = document.getElementById('toggle-pwd');
    const submitBtn = document.getElementById('submit-register');
    const confirmError = document.getElementById('confirm-error');

    // Requirements list items
    const reqLength = document.getElementById('req-length');
    const reqUpper = document.getElementById('req-upper');
    const reqLower = document.getElementById('req-lower');
    const reqNumber = document.getElementById('req-number');
    const reqValid = document.getElementById('req-valid');

    // Toggle password visibility
    if (togglePwdBtn) {
        togglePwdBtn.addEventListener('click', () => {
            const type = pwdInput.getAttribute('type') === 'password' ? 'text' : 'password';
            pwdInput.setAttribute('type', type);
            const icon = togglePwdBtn.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        });
    }

    const validatePassword = () => {
        const val = pwdInput.value;
        let isValid = true;

        // Length (6 to 8)
        if (val.length >= 6 && val.length <= 8) {
            setReqStatus(reqLength, true);
        } else {
            setReqStatus(reqLength, false);
            isValid = false;
        }

        // Upper
        if (/[A-Z]/.test(val)) {
            setReqStatus(reqUpper, true);
        } else {
            setReqStatus(reqUpper, false);
            isValid = false;
        }

        // Lower
        if (/[a-z]/.test(val)) {
            setReqStatus(reqLower, true);
        } else {
            setReqStatus(reqLower, false);
            isValid = false;
        }

        // Number
        if (/[0-9]/.test(val)) {
            setReqStatus(reqNumber, true);
        } else {
            setReqStatus(reqNumber, false);
            isValid = false;
        }

        // Valid symbols (only alphanumeric for this rule)
        if (val.length > 0 && /^[a-zA-Z0-9]+$/.test(val)) {
            setReqStatus(reqValid, true);
        } else {
            setReqStatus(reqValid, false);
            if (val.length > 0) isValid = false; 
            // if empty, we set to false but don't strictly show invalid until they type something not allowed, 
            // but for simplicity setting false is correct.
        }
        
        if (val.length === 0) {
             setReqStatus(reqValid, false);
             isValid = false;
        }

        return isValid;
    };

    const validateConfirm = () => {
        if (confirmInput.value === '') {
            confirmInput.classList.remove('error');
            confirmError.classList.remove('visible');
            return false;
        }

        if (confirmInput.value !== pwdInput.value) {
            confirmInput.classList.add('error');
            confirmError.classList.add('visible');
            return false;
        } else {
            confirmInput.classList.remove('error');
            confirmError.classList.remove('visible');
            return true;
        }
    };

    const validateEmailConfirm = () => {
        if (emailConfirmInput.value === '') {
            emailConfirmInput.classList.remove('error');
            emailConfirmError.classList.remove('visible');
            return false;
        }

        if (emailConfirmInput.value !== emailInput.value) {
            emailConfirmInput.classList.add('error');
            emailConfirmError.classList.add('visible');
            return false;
        } else {
            emailConfirmInput.classList.remove('error');
            emailConfirmError.classList.remove('visible');
            return true;
        }
    };

    const validatePhone = () => {
        const val = phoneInput.value.replace(/\s+/g, ''); // remove spaces
        if (val === '') {
            phoneInput.classList.remove('error');
            phoneError.classList.remove('visible');
            return false;
        }
        
        // Ensure exactly 9 digits
        if (!/^\d{9}$/.test(val)) {
            phoneInput.classList.add('error');
            phoneError.classList.add('visible');
            return false;
        } else {
            phoneInput.classList.remove('error');
            phoneError.classList.remove('visible');
            return true;
        }
    };

    const setReqStatus = (element, isValid) => {
        if (isValid) {
            element.classList.add('valid');
            element.classList.remove('invalid');
        } else {
            element.classList.add('invalid');
            element.classList.remove('valid');
        }
    };

    const checkFormValidity = () => {
        const isPwdValid = validatePassword();
        const isConfirmValid = validateConfirm();
        const isEmailConfirmValid = validateEmailConfirm();
        const isPhoneValid = validatePhone();
        const otherFieldsValid = form.checkValidity();

        if (isPwdValid && isConfirmValid && isEmailConfirmValid && isPhoneValid && otherFieldsValid) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    };

    // Event Listeners
    emailInput.addEventListener('input', () => {
        if (emailConfirmInput.value.length > 0) {
            validateEmailConfirm();
        }
        checkFormValidity();
    });

    emailConfirmInput.addEventListener('input', () => {
        validateEmailConfirm();
        checkFormValidity();
    });

    phoneInput.addEventListener('input', () => {
        // Automatically remove non-digits (optional, but requested: 'ingresar los otros nueve numeros')
        phoneInput.value = phoneInput.value.replace(/\D/g, '');
        if (phoneInput.value.length > 9) {
            phoneInput.value = phoneInput.value.slice(0, 9);
        }
        validatePhone();
        checkFormValidity();
    });

    pwdInput.addEventListener('input', () => {
        validatePassword();
        if (confirmInput.value.length > 0) {
            validateConfirm();
        }
        checkFormValidity();
    });

    confirmInput.addEventListener('input', () => {
        validateConfirm();
        checkFormValidity();
    });

    form.addEventListener('input', checkFormValidity);

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (submitBtn.disabled) return;
        
        // Save user data
        const userData = {
            name: document.getElementById('reg-name').value,
            phone: '+56 ' + document.getElementById('reg-phone').value,
            email: document.getElementById('reg-email').value
        };
        localStorage.setItem('registeredUser', JSON.stringify(userData));
        
        // Store login state
        localStorage.setItem('isLoggedIn', 'true');

        // Redirect to welcome email simulation
        window.location.href = 'welcome-email.html';
    });

    // Open login modal from register page
    const openLoginFromReg = document.getElementById('open-login-from-reg');
    if (openLoginFromReg) {
        openLoginFromReg.addEventListener('click', (e) => {
            e.preventDefault();
            if (typeof openLogin === 'function') {
                openLogin();
            } else {
                const loginModal = document.getElementById('login-modal');
                const loginOverlay = document.getElementById('login-overlay');
                if (loginModal && loginOverlay) {
                    loginModal.classList.add('active');
                    loginOverlay.classList.add('active');
                }
            }
        });
    }
});

