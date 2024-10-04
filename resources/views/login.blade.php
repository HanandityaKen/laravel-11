<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 22rem;">
      <h3 class="text-center mb-4">Login</h3>
      <form method="POST" id="loginForm">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter your email" name="email" required>
          <!-- error message untuk title -->
          @error('email')
            <div class="alert alert-danger mt-2">
                {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control @error('email') is-invalid @enderror" id="password" placeholder="Enter your password" name="password" required>
          @error('password')
            <div class="alert alert-danger mt-2">
                {{ $message }}
            </div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  {{-- <script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('http://127.0.0.1:8000/api/loginapi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();
            console.log(data)

            if (data.success) {
                console.log('Redirecting to:', data.redirect);
                localStorage.setItem('token', data.token);
                window.location.href = data.redirect;
            } else {
                console.error(data.message);
            }
        } catch (error) {
            console.error('Login failed:', error);
        }
    });
</script> --}}

  <script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('http://127.0.0.1:8000/api/loginapi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', 
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();
            console.log('Response Data:', data);

            if (response.ok) {
                localStorage.setItem('token', data.token);
                
                sessionStorage.setItem('email', data.user.email);
                sessionStorage.setItem('role', data.user.role);

                window.location.href = "{{ route('products.index') }}";
            } else {
                console.error('Error:', data.message);
            }
        } catch (error) {
            console.error('Login failed:', error);
        }
    });
  </script>

</body>
</html>
