<x-app-layout>
    @push('styles')
    @endpush


    Holis
    @foreach ($roles as $role)
        <span>
            {{ $role->name }}
        </span>
    @endforeach








    @push('scripts')
    @endpush

</x-app-layout>
