        @extends('layouts.guest')
        @section('content')
            <div class="auth-page-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center mt-sm-5 mb-4 text-white-50">
                                {{-- <div>
                                    <a href="index.html" class="d-inline-block auth-logo">
                                        <img src="assets/images/logo-light.png" alt="" height="20">
                                    </a>
                                </div> --}}
                                <p class="mt-3 fs-15 fw-medium">Daniel Rojas | Reservas Acceso</p>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card mt-4">

                                <div class="card-body p-4">
                                    <div class="text-center mt-2">
                                        <h5 class="text-primary">Bienvenido de nuevo !</h5>
                                        <p class="text-muted">Inicia sesion para trabajar.</p>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <form action="{{ route('login') }}" method="post">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="email"
                                                    class="form-label">{{ __('Correo Electronico') }}</label>
                                                <input type="text" id="email" name="email"
                                                    placeholder="Ingresa tu correo electronico" required autofocus
                                                    value="{{ old('email') }} "
                                                    class="form-control pe-5 @error('email') is-invalid @enderror">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="password">{{ __('Contraseña') }}</label>
                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                    <input type="password" placeholder="Ingresa Contraseña" id="password"
                                                        name="password" required
                                                        class="form-control pe-5 @error('password') is-invalid @enderror">

                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                        type="button" id="tooglePassword"><i
                                                            class="ri-eye-fill align-middle"></i></button>
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>



                                            <div class="mt-4">
                                                <button class="btn btn-success w-100"
                                                    type="submit">{{ __('Iniciar Sesion') }}</button>
                                            </div>
                                            {{--
                                            <div class="mt-4 text-center">
                                                <div class="signin-other-title">
                                                    <h5 class="fs-13 mb-4 title">Sign In with</h5>
                                                </div>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-primary btn-icon waves-effect waves-light"><i
                                                            class="ri-facebook-fill fs-16"></i></button>
                                                    <button type="button"
                                                        class="btn btn-danger btn-icon waves-effect waves-light"><i
                                                            class="ri-google-fill fs-16"></i></button>
                                                    <button type="button"
                                                        class="btn btn-dark btn-icon waves-effect waves-light"><i
                                                            class="ri-github-fill fs-16"></i></button>
                                                    <button type="button"
                                                        class="btn btn-info btn-icon waves-effect waves-light"><i
                                                            class="ri-twitter-fill fs-16"></i></button>
                                                </div>
                                            </div> --}}
                                        </form>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->

                            <div class="mt-4 text-center">
                                <p class="mb-0">No tienes Cuenta? ? <a href="{{ route('register') }}"
                                        class="fw-semibold text-primary text-decoration-underline"> Registrate </a> </p>
                            </div>

                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const togglePassword = document.querySelector('#tooglePassword');
                        const passwordInput = document.querySelector('#password');
                        togglePassword.addEventListener('click', function() {
                            // Toggle the type attribute
                            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                            passwordInput.setAttribute('type', type);

                        });
                    });
                </script>
            @endpush
        @endsection
