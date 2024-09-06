<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include your CSS here -->
</head>
<body style="background: lightgray">
    @include('layouts.header')
    <main>
        @yield('content')
    </main>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Additional footer or content -->
            </div>
        </div>
    </div>
    <!-- Include your scripts here -->
</body>
</html>
