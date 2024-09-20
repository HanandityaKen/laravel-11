@extends('main')

@section('content')
    

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        {{-- @if ($role === 'admin')
                            <a href="{{ route('products.create') }}" class="btn btn-md btn-success mb-3">ADD PRODUCT</a>
                        @endif --}}
                        <div id="roleActions"></div>
                        <table id="productsTable" class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th>Stock</th>
                                    <th>File</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                        {{-- <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">IMAGE</th>
                                    <th scope="col">TITLE</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col">STOCK</th>
                                    <th scope="col">FILE</th>
                                    <th scope="col" style="width: 20%">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ asset('/storage/products/'.$product->images) }}" class="rounded" style="width: 150px">
                                        </td>
                                        <td>{{ $product->title }}</td>
                                        <td>{{ "Rp " . number_format($product->price,2,',','.') }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{{ $product->file}}</td>
                                        <td class="text-center">
                                            <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-dark">SHOW</a>
                                                @if ($role === 'admin')
                                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Data Products belum Tersedia.
                                    </div>
                                @endforelse
                            </tbody>
                        </table> --}}
                        {{-- {{ $products->links() }}
                        <div class="pagination">
                            {{ $products->links() }}
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')

<script>
    // Fungsi untuk memeriksa apakah pengguna sudah login
    // function checkLogin() {
    //     const token = localStorage.getItem('token');
    //     const email = sessionStorage.getItem('email');

    //     console.log(token)
    //     console.log(email)

    //     if (!token || !email) {
    //         window.location.href = "{{ route('login') }}"; // Ganti dengan rute login yang sesuai
    //     }
    // }

    // checkLogin();

    // SweetAlert message
    @if(session('success'))
        Swal.fire({
            icon: "success",
            title: "BERHASIL",
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @elseif(session('error'))
        Swal.fire({
            icon: "error",
            title: "GAGAL!",
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    $(document).ready(function() {
        const token = localStorage.getItem('token');
        console.log(token);

        $('#productsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('products.data.api') }}',
                type: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            },
            columns: [
                {data: 'image', name: 'image', orderable: false},
                {data: 'title', name: 'title'},
                {data: 'price', name: 'price', searchable: false},
                {data: 'description', name: 'description', searchable: false},
                {data: 'stock', name: 'stock', searchable: false},
                {data: 'file', name: 'file', searchable: false},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ]
        });
    });

    document.addEventListener('DOMContentLoaded', async function() {
        const token = localStorage.getItem('token');

        try {
            const response = await fetch('http://127.0.0.1:8000/api/products-api', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            const roleData = await response.json();
            const role = roleData.role;
            console.log(role);

            if (role === 'admin') {
                document.getElementById('roleActions').innerHTML = `
                    <a href="{{ route('products.create') }}" class="btn btn-md btn-success mb-3">ADD PRODUCT</a>
                `;
            }

        } catch (error) {
            console.error('Failed to fetch products:', error);
        }
    });
</script>



@endpush



