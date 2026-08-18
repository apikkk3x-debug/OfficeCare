<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OfficeCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-xl shadow-md w-96">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-6">OfficeCare Login</h2>
        
        @if(session('error'))
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-semibold mb-2">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label class="block text-slate-700 text-sm font-semibold mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Masuk</button>
        </form>

        <div class="mt-6 text-xs text-slate-500 text-center">
            <p>Gunakan akun seeder:</p>
            <p>Admin: admin@officecare.com</p>
            <p>Karyawan: karyawan@officecare.com</p>
            <p>Pimpinan: pimpinan@officecare.com</p>
            <p class="mt-1">Password semua: <b>123</b></p>
        </div>
    </div>
</body>
</html>