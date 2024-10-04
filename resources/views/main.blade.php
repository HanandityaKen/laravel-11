<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include your CSS here -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">
    @include('layouts.header')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Additional footer or content -->
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Include your scripts here -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        async function refreshToken() {
            const token = localStorage.getItem('token');

            if (!token) {
                console.log('Token not found');
            }

            try {
                const response = await fetch('http://127.0.0.1:8000/api/refresh-token-api', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                    },
                });
                const data = await response.json();
                console.log(data)

                if (response.ok) {
                    localStorage.setItem('token', data.token);
                    return data.token;
                } else {
                    window.location.href = "{{ route('login') }}";
                }
            } catch (error) {
                console.error('Token refresh failed:', error);
                window.location.href = "{{ route('login') }}";
            }
        }




        function checkLogin() {
            const token = localStorage.getItem('token');
            const email = sessionStorage.getItem('email');

            if (!token || !email) {
                window.location.href = "{{ route('login') }}"; // Ganti dengan rute login yang sesuai
            }
        }

        checkLogin();
    </script>
    

    @stack('scripts')

</body>
</html>
