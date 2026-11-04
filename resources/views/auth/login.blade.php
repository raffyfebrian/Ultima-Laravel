
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Staf | Ngoffe ID</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background: #f3f5f4;
            font-family: Arial, sans-serif;
            color: #202b29;
        }
        .card {
            width: 100%;
            max-width: 400px;
            padding: 32px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 35px #102b2412;
        }
        .brand { color: #01796F; font-size: 26px; font-weight: 800; }
        h1 { margin-bottom: 8px; font-size: 24px; }
        .muted { color: #687572; font-size: 14px; line-height: 1.5; }
        label { display: block; margin: 18px 0 7px; font-size: 14px; }
        input[type=email], input[type=password] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccd5d2;
            border-radius: 8px;
        }
        .remember { display: flex; align-items: center; gap: 8px; font-size: 14px; }
        button {
            width: 100%;
            margin-top: 22px;
            padding: 13px;
            border: 0;
            border-radius: 8px;
            background: #01796F;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .error { color: #c62828; font-size: 13px; margin-top: 6px; }
    </style>
</head>
<body>
    <main class="card">
        <div class="brand">NGOFFE ID</div>
        <h1>Login Staf</h1>
        <p class="muted">Masuk untuk mengakses sistem operasional Ngoffe ID.</p>

        @if ($errors->any())
            <div class="error">Email atau password tidak valid. Silakan coba lagi.</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}" required autofocus
                   autocomplete="username">

            <label for="password">Password</label>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password">

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>

            <button type="submit">Masuk</button>
        </form>
    </main>
</body>
</html>
