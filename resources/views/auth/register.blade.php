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
                                        <h5 class="text-primary">Crear una cuenta !</h5>
                                        <p class="text-muted">Registrate para iniciar.</p>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <form class="needs-validation" action="{{ route('register') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf



                                            <div class="mb-3">
                                                <label for="nombres" class="form-label">{{ __('Nombre') }} <span
                                                        class="text-danger">*</span> </label>
                                                <input type="text" id="nombres" name="nombres"
                                                    placeholder="Ingresa tu nombre: " required autofocus
                                                    value="{{ old('nombres') }} "
                                                    class="form-control pe-5 @error('nombres') is-invalid @enderror">
                                                @error('nombres')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>


                                            <div class="mb-3">
                                                <label for="apellidos" class="form-label">{{ __('Apellido') }} <span
                                                        class="text-danger">*</span> </label>
                                                <input type="text" id="apellidos" name="apellidos"
                                                    placeholder="Ingresa tus Apellidos: " required autofocus
                                                    value="{{ old('apellidos') }} "
                                                    class="form-control pe-5 @error('apellidos') is-invalid @enderror">
                                                @error('apellidos')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>


                                            <div class="mb-3">
                                                <label for="telefono" class="form-label">{{ __('Telefono') }} <span
                                                        class="text-danger">*</span> </label>
                                                <input type="text" id="telefono" name="telefono"
                                                    placeholder="Ingresa tu telefono: " required autofocus
                                                    value="{{ old('telefono') }} "
                                                    class="form-control pe-5 @error('telefono') is-invalid @enderror">
                                                @error('telefono')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

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


                                            <div>
                                                <label for="foto" class="form-label">foto</label>

                                                <input class="form-control" type="file" id="foto" name="foto">
                                            </div>


                                            <div class="mb-3">
                                                <label class="form-label" for="password">{{ __('Contraseña') }}</label>
                                                <input type="password" placeholder="Ingresa Contraseña" id="password"
                                                    name="password" required
                                                    class="form-control pe-5 @error('password') is-invalid @enderror">


                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>


                                            <div class="mb-3">
                                                <label class="form-label"
                                                    for="password_confirmation">{{ __('Confirmar Contraseña') }}</label>
                                                <input type="password" placeholder="Confirma Contraseña"
                                                    id="password_confirmation" name="password_confirmation" required
                                                    class="form-control pe-5 @error('password_confirmation') is-invalid @enderror">


                                                @error('password_confirmation')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>



                                            <div class="mt-4">
                                                <button class="btn btn-success w-100"
                                                    type="submit">{{ __('Registrarte') }}</button>
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
                                <p class="mb-0">Ya tienes cuenta? <a href="{{ route('login') }}"
                                        class="fw-semibold text-primary text-decoration-underline"> Inicia Sesion </a> </p>
                            </div>

                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
        @endsection


        {{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
