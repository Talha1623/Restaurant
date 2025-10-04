<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            display: flex;
            width: 1000px;
            height: auto;
            min-height: 600px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .register-left {
            flex: 1.2;
            background: linear-gradient(135deg, #00b94c 0%, #009c40 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .register-left::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -50px;
            left: -50px;
        }
        
        .register-left::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
        }
        
        .register-logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            z-index: 2;
        }
        
        .register-logo-icon {
            font-size: 32px;
            background: white;
            color: #00b94c;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .register-logo-text {
            font-weight: 700;
            font-size: 24px;
        }
        
        .register-left h2 {
            font-size: 32px;
            margin-bottom: 20px;
            z-index: 2;
        }
        
        .register-left p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 30px;
            z-index: 2;
        }
        
        .features-list {
            list-style: none;
            z-index: 2;
        }
        
        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 15px;
        }
        
        .features-list li i {
            margin-right: 10px;
            background: rgba(255, 255, 255, 0.2);
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .register-right {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .register-header h2 {
            font-size: 28px;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .register-header p {
            color: #718096;
            font-size: 15px;
        }
        
        .register-form {
            width: 100%;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group.full-width {
            grid-column: span 2;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2d3748;
            font-size: 14px;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            font-size: 16px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #00b94c;
            box-shadow: 0 0 0 3px rgba(0, 185, 76, 0.15);
            background: white;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            cursor: pointer;
            font-size: 16px;
        }
        
        .terms-container {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            font-size: 14px;
            color: #4a5568;
            grid-column: span 2;
        }
        
        .terms-container input {
            margin-top: 4px;
            margin-right: 10px;
            accent-color: #00b94c;
        }
        
        .terms-container a {
            color: #00b94c;
            text-decoration: none;
            font-weight: 500;
        }
        
        .terms-container a:hover {
            text-decoration: underline;
        }
        
        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #00b94c 0%, #009c40 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 185, 76, 0.25);
            margin-bottom: 20px;
            grid-column: span 2;
            font-family: 'Poppins', sans-serif;
        }
        
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 185, 76, 0.35);
            background: linear-gradient(135deg, #00c952 0%, #00a844 100%);
        }
        
        .register-btn:active {
            transform: translateY(0);
        }
        
        .login-link {
            text-align: center;
            font-size: 14px;
            color: #718096;
            margin-top: 10px;
            grid-column: span 2;
        }
        
        .login-link a {
            color: #00b94c;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        
        .login-link a:hover {
            color: #009c40;
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .register-container {
                flex-direction: column;
                height: auto;
                width: 100%;
                max-width: 500px;
            }
            
            .register-left {
                padding: 30px;
            }
            
            .register-right {
                padding: 30px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
            
            .terms-container {
                grid-column: span 1;
            }
            
            .register-btn {
                grid-column: span 1;
            }
            
            .login-link {
                grid-column: span 1;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .register-left {
            animation: fadeIn 0.8s ease forwards;
        }
        
        .register-right {
            animation: fadeIn 0.8s 0.3s ease forwards;
            opacity: 0;
        }
        
        /* Checkbox styling */
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
        }
        
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
            font-size: 13px;
            color: #718096;
            grid-column: span 2;
        }
        
        .security-note i {
            color: #00b94c;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <div class="register-logo">
                <div class="register-logo-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div class="register-logo-text">POS System</div>
            </div>
            
            <h2>Create Your Account</h2>
            <p>Join thousands of restaurants using our POS system to streamline their operations and grow their business.</p>
            
            <ul class="features-list">
                <li><i class="fas fa-check"></i> Manage orders and inventory in real-time</li>
                <li><i class="fas fa-check"></i> Track sales performance with analytics</li>
                <li><i class="fas fa-check"></i> Process payments securely</li>
                <li><i class="fas fa-check"></i> Generate detailed financial reports</li>
            </ul>
        </div>
        
        <div class="register-right">
            <div class="register-header">
                <h2>Get Started</h2>
                <p>Create your account in just a few steps</p>
            </div>
            
            <form class="register-form" action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <div class="input-with-icon">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter full name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="input-with-icon">
                            <div class="input-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number" required>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="restaurant_name">Restaurant Name</label>
                        <div class="input-with-icon">
                            <div class="input-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <input type="text" id="restaurant_name" name="restaurant_name" class="form-control" placeholder="Enter restaurant name" required>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="email">Email Address</label>
                        <div class="input-with-icon">
                            <div class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Create password" required>
                            <span class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-with-icon">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                            <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="terms-container">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                    </div>
                    
                    <button type="submit" class="register-btn">Create Account</button>
                </div>
            </form>
            
            <div class="security-note">
                <i class="fas fa-shield-alt"></i>
                <span>Your information is securely encrypted</span>
            </div>
            
            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        const registerForm = document.querySelector('.register-form');
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const terms = document.getElementById('terms').checked;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('Please agree to the Terms of Service and Privacy Policy');
                return;
            }
        });
        
        // Password visibility toggle
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = passwordField.nextElementSibling.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Add subtle animation to register button
        const registerBtn = document.querySelector('.register-btn');
        registerBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 20px rgba(0, 185, 76, 0.35)';
        });
        
        registerBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 5px 15px rgba(0, 185, 76, 0.25)';
        });
        
        // Add focus effects to form inputs
        const formInputs = document.querySelectorAll('.form-control');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#00b94c';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#718096';
            });
        });
    </script>
</body>
</html>