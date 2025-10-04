<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            width: 400px;
            padding: 50px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        .login-header { text-align: center; margin-bottom: 40px; }
        .login-header h2 { font-size: 28px; color: #2d3748; margin-bottom: 10px; }
        .login-header p { color: #718096; font-size: 15px; }
        .login-form { width: 100%; }
        .form-group { margin-bottom: 25px; position: relative; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #2d3748; font-size: 14px; }
        .input-with-icon { position: relative; }
        .input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #718096; font-size: 18px; }
        .form-control {
            width: 100%; padding: 15px 15px 15px 50px; border: 1px solid #e2e8f0;
            border-radius: 10px; font-size: 15px; transition: all 0.3s ease; background: #f8fafc;
        }
        .form-control:focus { outline: none; border-color: #00b94c; box-shadow: 0 0 0 3px rgba(0, 185, 76, 0.15); background: white; }
        .remember-me { display: flex; align-items: center; margin-bottom: 25px; font-size: 14px; color: #4a5568; }
        .remember-me input { margin-right: 8px; accent-color: #00b94c; }
        .login-btn {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, #00b94c 0%, #009c40 100%);
            color: white; border: none; border-radius: 10px;
            font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 185, 76, 0.25); margin-bottom: 20px;
        }
        .login-btn:hover {
            transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 185, 76, 0.35);
            background: linear-gradient(135deg, #00c952 0%, #00a844 100%);
        }
        .login-btn:active { transform: translateY(0); }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Login</h2>
            <p>Enter your credentials to access the system</p>
        </div>
        
        <form class="login-form" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-with-icon">
                    <div class="input-icon"><i class="fas fa-envelope"></i></div>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <div class="input-icon"><i class="fas fa-lock"></i></div>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Keep me logged in</label>
            </div>
            
            <button type="submit" class="login-btn">Login to Dashboard</button>
        </form>
    </div>
</body>
</html>
