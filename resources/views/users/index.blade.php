@extends('main')

@section('content')
    <div class="row mb-10">
        <div class="col-lg-4">
            <!-- Foto Profil -->
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    {{-- <img src="https://via.placeholder.com/150" id="profile-image" alt="Foto Profil" class="img-fluid rounded-circle mb-3" width="150" height="150"><br> --}}
                    <img src="https://via.placeholder.com/150" id="profile-image" alt="Foto Profil" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;"><br>
                    <h5 class="card-title" id="name-user-title"></h5>
                    <p class="card-text text-muted mb-3" id="email-user-title"></p>
                    <input type="file" id="upload-photo-input" style="display: none;" name="photo">
                    <button class="btn btn-primary mb-1" id="buttonUploadPhoto">Upload Image</button>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <!-- Detail Profil -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Profil</h6>
                </div>
                <div class="card-body mb-4">
                    <form>
                        <!-- Nama Pengguna -->
                        {{-- <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" id="name-user" value="-" disabled>
                        </div> --}}
                        <!-- Email Pengguna -->
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email-user" value="-">
                        </div>
                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" class="form-control" id="user-old-pass" value="">
                        </div>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" class="form-control" id="user-new-pass" value="">
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" class="form-control" id="user-confirm-pass" value="">
                        </div>
                        <!-- Tombol Edit Profil -->
                        <a href="#" class="btn btn-primary">Edit Profil</a>
                    </form>
                </div>
            </div>
        </div>
    </div> 
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getUserData();
            uploadImage();
        })

        async function getUserData() {
            const id = {{ request()->route('id') }};
            const token = localStorage.getItem('token')

            try {
                const response = await fetch(`http://127.0.0.1:8000/api/user-data-api/${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                })

                if (!response.ok) {
                    throw new Error(`${response.status}`);
                }

                const data = await response.json();

                // document.getElementById('name-user').value = data.user.name;
                document.getElementById('email-user').value = data.user.email;
                
                document.getElementById('name-user-title').textContent = data.user.name;
                document.getElementById('email-user-title').textContent = data.user.email;

                if (data.user.photo) {
                    document.getElementById('profile-image').src = `{{ asset('/storage/user/') }}/${data.user.photo}`;
                }


            } catch (error) {
                await refreshToken(401)
                getUserData()
                // console.error()
            }
        }

        async function uploadImage() {
            const id = {{ request()->route('id') }};
            const token = localStorage.getItem('token')

            document.getElementById('buttonUploadPhoto').addEventListener('click', function() {
                document.getElementById('upload-photo-input').click();
            });

            document.getElementById('upload-photo-input').addEventListener('change', function(event) {
                const photoInput = event.target;
                const photoName = photoInput.files[0].name;

                const formData = new FormData();
                formData.append('photo', photoInput.files[0]);

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda ingin menyimpan file ${photoName}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    cancelButtonText: 'Cancel'
                }).then(async(result) => {
                    if (result.isConfirmed){
                        try {
                            const response = await fetch(`http://127.0.0.1:8000/api/user-upload-photo-api/${id}`, {
                                method: 'POST',
                                headers: {
                                    'Authorization': `Bearer ${token}`,
                                    'Accept': 'application/json'
                                },
                                body: formData,
                            });

                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }

                            if (response.status === 200) {
                                Swal.fire({
                                    title: 'Foto berhasil disimpan',
                                    icon: 'success',
                                }).then(() => {
                                    getUserData()
                                })
                            } else {
                                alert(data.message);
                            }
                        } catch (error) {
                            await refreshToken(401);
                            uploadImage()
                            // console.error()
                        }
                    } else {
                        console.log('NOOO')
                    }
                })
            });
        }
    </script>
@endpush
