@extends('main')

@section('title', 'Products')

@section('content')
    
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <form method="POST" id="createForm" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">IMAGE</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" name="images" id="images">

                            @error('images')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">TITLE</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Enter Product Title">

                            @error('title')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">DESCRIPTION</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Enter Product Description">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">PRICE</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="Enter Product Price">

                                    @error('price')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">STOCK</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" placeholder="Enter Product Stock">

                                    @error('stock')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">DOCUMENT</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" id="file">

                                    @error('file')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
                        <button type="reset" class="btn btn-md btn-warning">RESET</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        CKEDITOR.replace( 'description' );

        // async function refreshToken() {
        //     const token = localStorage.getItem('token');

        //     if (!token) {
        //         console.log('Token not found');
        //     }

        //     try {
        //         const response = await fetch('http://127.0.0.1:8000/api/refresh-token-api', {
        //             method: 'POST',
        //             headers: {
        //                 'Authorization': `Bearer ${token}`,
        //                 'Content-Type': 'application/json',
        //             },
        //         });
        //         const data = await response.json();
        //         console.log(data)

        //         if (response.ok) {
        //             localStorage.setItem('token', data.token);
        //             return data.token;
        //         } else {
        //             window.location.href = "{{ route('login') }}";
        //         }
        //     } catch (error) {
        //         console.error('Token refresh failed:', error);
        //         window.location.href = "{{ route('login') }}";
        //     }
        // }

        async function submitForm(event) {
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

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                if (response.status === 201) {
                    Swal.fire({
                        title: 'Data berhasil disimpan',
                        icon: 'success',
                    });

                    setTimeout(function() {
                        window.location.href = "{{ route('products.index') }}"
                    }, 2000);

                } else {
                    alert(data.message);
                }


            } catch (error) {
                // if (error.message.includes('401')) {
                // }
                await refreshToken(401)
                submitForm()
                // console.error(error)
                
            }
        }

        document.getElementById('createForm').addEventListener('submit', submitForm)


    </script>
@endpush