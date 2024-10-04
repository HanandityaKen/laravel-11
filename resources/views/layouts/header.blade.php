<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laravel 11</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Header -->
  <header class="bg-light border-bottom py-3">
    <div class="container d-flex justify-content-between align-items-center">
      <a href="#" class="navbar-brand">
        <!-- Logo -->
        {{-- <img src="logo.png" alt="Logo" height="40"> --}}
      </a>
      <!-- Navigation Links -->
      <nav class="d-flex">
        {{-- <a href="#" class="nav-link">Home</a>
        <a href="#" class="nav-link">About</a>
        <a href="#" class="nav-link">Services</a>
        <a href="#" class="nav-link">Contact</a> --}}
      </nav>
      <!-- User Actions -->
      <div class="d-flex">
        {{-- <a href="#" class="btn btn-outline-primary me-2">Login</a> --}}
        {{-- <form action="{{route('logoutproses')}}" method="POST">
          @csrf --}}
        <button id="logoutButton" class="btn btn-primary">Logout</button>
        {{-- </form> --}}
      </div>
    </div>
  </header>

  <!-- Optional JavaScript; choose one of the two! -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('logoutButton').addEventListener('click', async function() {
      const token = localStorage.getItem('token');
      // const email = sessionStorage.getItem('email');

      // console.log(token)
      // console.log(email)

      try {
          const response = await fetch('http://127.0.0.1:8000/api/logout-api', {
              method: 'POST',
              headers: {
                  'Authorization': `Bearer ${token}`,
                  'Accept': 'application/json'
              }
          });

          if (response.ok) {
              localStorage.removeItem('token');
              sessionStorage.removeItem('email');
              window.location.href = "{{ route('login') }}"; // Ganti dengan rute login yang sesuai
          } else {
              console.error('Logout failed:', await response.json());
          }
      } catch (error) {
          console.error('Logout request failed:', error);
      }
    });
  </script>
</body>
</html>
