document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    loginForm.addEventListener('submit', async(event) => {
        event.preventDefault();

        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        // التحقق من صحة البريد الإلكتروني باستخدام regex
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showMessage('Please enter a valid email address.', 'info');
            return;
        }

        // التحقق من أن كلمة المرور ليست فارغة
        if (!password) {
            showMessage('Password cannot be empty.', 'error');
            return;
        }

        try {
            const response = await fetch('api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email, password: password })
            });

            const data = await response.json();

            if (response.ok) {
                // console.log('Login successful:', data);
                showMessage('Login successful!', 'success');
                setTimeout(() => window.location.href = './', 1000);
            } else {
                // عرض رسالة الخطأ من الخادم
                if (data && data.error) {
                    showMessage(data.error, "error");
                } else {
                    showMessage('An unexpected error occurred during login.', 'error');
                }
            }
        } catch (error) {
            // console.error('Fetch error:', error);
            showMessage('Failed to connect to the server.', "error");
        }
    });
});