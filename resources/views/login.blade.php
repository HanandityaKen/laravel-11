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
      <form action="{{ route('loginproses')}}" method="POST">
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
</body>
</html>
