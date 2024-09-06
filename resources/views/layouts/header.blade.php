<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header Template</title>
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
        <form action="{{route('logoutproses')}}" method="POST">
          @csrf
          <button type="submit" class="btn btn-primary">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <!-- Optional JavaScript; choose one of the two! -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
