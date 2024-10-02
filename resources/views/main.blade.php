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
        // async function refreshToken() {
        //     const token = localStorage.getItem('token');
            
        //     if (!token) {
        //         window.location.href = "{{ route('login') }}"; 
        //         return;
        //     }

        //     try {
        //         const response = await fetch('http://127.0.0.1:8000/api/refresh-token-api', { 
        //             method: 'POST',
        //             headers: {
        //                 'Authorization': `Bearer ${token}`,
        //                 'Content-Type': 'application/json',
        //             }
        //         });

        //         if (!response.ok) {
        //             throw new Error('Failed to refresh token');
        //         }

        //         const data = await response.json();
        //         if (data.token) {
        //             localStorage.setItem('token', data.token);
        //             console.log('Token refreshed successfully');
        //         } else {
        //             console.log('No new token received');
        //         }
        //     } catch (error) {
        //         console.error('Error refreshing token:', error);
        //         // window.location.href = "{{ route('login') }}"; // Ganti dengan rute login yang sesuai
        //     }
        // }

        async function refreshToken() {
            const refreshToken = localStorage.getItem('refresh_token');

            if (!refreshToken) {
                console.log('Refresh token tidak ditemukan. Redirect ke halaman login.');
            }

            try {
                const response = await fetch('http://127.0.0.1:8000/api/refresh-token-api', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${refreshToken}`,
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    localStorage.setItem('access_token', data.access_token);
                    return data.access_token;
                } else {
                    window.location.href = "{{ route('login') }}";
                }
            } catch (error) {
                console.error('Token refresh failed:', error);
                window.location.href = "{{ route('login') }}"; // Arahkan ke login jika ada error
            }
        }

        // refreshToken();




        function checkLogin() {
            const access_token = localStorage.getItem('access_token');
            const refresh_token = localStorage.getItem('refresh_token');
            console.log(access_token)
            console.log(refresh_token)
            const email = sessionStorage.getItem('email');

            if (!access_token || !email) {
                window.location.href = "{{ route('login') }}"; // Ganti dengan rute login yang sesuai
            }
        }

        checkLogin();
    </script>
    

    @stack('scripts')

</body>
</html>
