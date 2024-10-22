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
                        <div id="show-perPage">
                            <label for="perPage">Show per page:</label>
                            <select id="perPage">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
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
                            <tbody>

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="data-info">
                                Total Products: <span id="total-data">0</span>
                            </div>                
                            <div id="pagination-controls">
                                <button id="prev-page" disabled>Previous</button>
                                <span id="current-page">1</span>
                                <button id="next-page" disabled>Next</button>
                            </div>
                        </div>
                        
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

    // SweetAlert message
    // @if(session('success'))
    //     Swal.fire({
    //         icon: "success",
    //         title: "BERHASIL",
    //         text: "{{ session('success') }}",
    //         showConfirmButton: false,
    //         timer: 2000
    //     });
    // @elseif(session('error'))
    //     Swal.fire({
    //         icon: "error",
    //         title: "GAGAL!",
    //         text: "{{ session('error') }}",
    //         showConfirmButton: false,
    //         timer: 2000
    //     });
    // @endif

    document.addEventListener('DOMContentLoaded', function() {
        getRole();
        renderTable();
        
    });

    // $(document).ready(function() {

    //     function loadDatatable() {

    //         const token = localStorage.getItem('token');
    
    //         $('#productsTable').DataTable({
    //             processing: true,
    //             serverSide: true,
    //             destroy: true,
    //             ajax: {
    //                 url: '{{ route('products.data.api') }}',
    //                 type: 'GET',
    //                 headers: {
    //                     'Authorization': `Bearer ${token}`,
    //                     'Accept': 'application/json'
    //                 },
    //                 error: async function(xhr, status, error) {
    //                     if (xhr.status === 401) {
    //                         await refreshToken()

    //                         loadDatatable(); 
    //                     } else {
    //                         console.error('Error fetching data:', error);
    //                     }
    //                 }
    //             },
    //             columns: [
    //                 {data: 'image', name: 'image', orderable: false},
    //                 {data: 'title', name: 'title'},
    //                 {data: 'price', name: 'price', orderable: false, searchable: false},
    //                 {data: 'description', name: 'description', orderable: false, searchable: false},
    //                 {data: 'stock', name: 'stock', orderable: false, searchable: false},
    //                 {data: 'file', name: 'file', orderable: false, searchable: false},
    //                 {data: 'actions', name: 'actions', orderable: false, searchable: false}
    //             ]
    //         });
    //     }

    //     loadDatatable()

    // });

    let currentPage = 1;
    let perPage = 10;

    async function renderTable(page = 1, perPage = 10) {
        const token = localStorage.getItem('token')

        try {
            const response = await fetch(`http://127.0.0.1:8000/api/products-data-api?page=${page}&per_page=${perPage}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
            })

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`)
            }

            const data = await response.json()

            const tbody = document.querySelector('#productsTable tbody');
            tbody.innerHTML = '';

            //jquery
            // const tbody = $('#productsTable tbody')
            // tbody.empty()
    
            data.data.forEach(product => {
                const row = `
                    <tr>
                        <input type="hidden" id="product-id" value="${product.id}">
                        <td>${product.images}</td>    
                        <td>${product.title}</td>    
                        <td>${product.price}</td>    
                        <td>${product.description}</td>    
                        <td>${product.stock}</td>    
                        <td>${product.file}</td>    
                        <td>${product.actions}</td>    
                    </tr>
                `
                tbody.insertAdjacentHTML('beforeend', row);

                // tbody.appendChild(row);
                //jquery
                // tbody.append(row)
            });

            document.getElementById('total-data').textContent = data.pagination.total;

            document.getElementById('current-page').textContent = data.pagination.current_page;

            document.getElementById('prev-page').disabled = !data.pagination.prev_page_url;
            document.getElementById('next-page').disabled = !data.pagination.next_page_url;

        } catch (error) {
            await refreshToken(401)
            renderTable
        }
    }

    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderTable(currentPage, perPage);
        }
    });

    document.getElementById('next-page').addEventListener('click', () => {
        currentPage++;
        renderTable(currentPage, perPage);
    });

    document.getElementById('perPage').addEventListener('change', (event) => {
        perPage = event.target.value;
        currentPage = 1; // reset ke halaman pertama
        renderTable(currentPage, perPage);
    });

    renderTable(currentPage, perPage);


    //jquery
    // $('#productsTable').on('click', '.delete-product', async function() {

    //     const id = $(this).data('id'); 
    //     const token = localStorage.getItem('token'); 


    //     if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
    //         try {
    //             const response = await fetch(`http://127.0.0.1:8000/api/products-delete/${id}`, {
    //                 method: 'DELETE',
    //                 headers: {
    //                     'Authorization': `Bearer ${token}`,
    //                     'Accept': 'application/json'
    //                 }
    //             });

    //             if (response.status === 200) {
    //                 window.location.href = "{{ route ('products.index') }}"
    //                 alert('Produk berhasil dihapus!');
    //             } else {
    //                 alert('Gagal menghapus produk.');
    //             }
    //         } catch (error) {
    //             console.error('Kesalahan:', error);
    //         }
    //     }
    // });

    document.getElementById('productsTable').addEventListener('click', async function(event) {
        if (event.target.classList.contains('delete-product')) {
            // const id = $(this).data('id')
            const id = event.target.closest('tr').querySelector('#product-id').value
            const token = localStorage.getItem('token')

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Product ini akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`http://127.0.0.1:8000/api/products-delete/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.status === 200) {
                            Swal.fire(
                                'Dihapus!',
                                'Produk berhasil dihapus',
                                'success',
                            ).then(() => {
                                renderTable();
                            })
                        } else {
                            Swal.fire(
                                'Gagal',
                                'Gagal meghapus produk',
                                'error'
                            )
                        }
                    } catch (error) {
                        await refreshToken('401')
                        getRole()               
                    }
                }
            })

        }
    });

    async function getRole() {
        const token = localStorage.getItem('token');
        console.log(token)

        try {
            const response = await fetch('http://127.0.0.1:8000/api/products-role-api', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const roleData = await response.json();
            const role = roleData.role;
            console.log(role);

            if (role === 'admin') {
                document.getElementById('roleActions').innerHTML = `
                    <a href="{{ route('products.create') }}" class="btn btn-md btn-success mb-3">ADD PRODUCT</a>
                `;
            }

        } catch (error) {
            await refreshToken('401')
            getRole()

            // if (error.message.includes('401')) {
            // }
        }
    };
</script>



@endpush



