<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add New Products - SantriKoding.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form id="createForm" method="POST" enctype="multipart/form-data">
                        
                            @csrf

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">IMAGE</label>
                                <input type="file" id="images" class="form-control @error('images') is-invalid @enderror" name="images">
                            
                                <!-- error message untuk image -->
                                @error('images')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">TITLE</label>
                                <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="Masukkan Judul Product">
                            
                                <!-- error message untuk title -->
                                @error('title')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">DESCRIPTION</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Masukkan Description Product">{{ old('description') }}</textarea>
                            
                                <!-- error message untuk description -->
                                @error('description')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">PRICE</label>
                                        <input type="number" id="price" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" placeholder="Masukkan Harga Product">
                                    
                                        <!-- error message untuk price -->
                                        @error('price')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">STOCK</label>
                                        <input type="number" id="stock" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock') }}" placeholder="Masukkan Stock Product">
                                    
                                        <!-- error message untuk stock -->
                                        @error('stock')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold" for="">File</label>
                                <input type="file" id="file" class="form-control @error('file') is-invalid @enderror" name="file" value="{{ old('file')}}" placeholder="Masukan File">
                                @error('file')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>

                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'description' );
    </script>
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

        document.getElementById('createForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const token = localStorage.getItem('token');
            // console.log(token)

            const images = document.getElementById('images').files[0];
            const title = document.getElementById('title').value;
            const description = CKEDITOR.instances['description'].getData(); 
            const price = document.getElementById('price').value;
            const stock = document.getElementById('stock').value;
            const file = document.getElementById('file').files[0];

            console.log(images)
            console.log(title)
            console.log(description)
            console.log(price)
            console.log(stock)
            console.log(file)

            const formData = new FormData();
            formData.append('images', images);
            formData.append('title', title);
            formData.append('description', description);
            formData.append('price', price);
            formData.append('stock', stock);
            formData.append('file', file);

            try {
                const response = await fetch('http://127.0.0.1:8000/api/products-store-api', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData,
                })

                if (response.status === 200) {
                    window.location.href = "{{ route('products.index') }}"
                } else if {
                    refreshToken()
                    window.location.reload()
                } else {
                    alert(data.message);
                }


            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan, silakan coba lagi.');
            }
        })


    </script>
</body>
</html>