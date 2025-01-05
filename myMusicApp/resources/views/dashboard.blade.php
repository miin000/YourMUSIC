<x-app-layout>
    <x-slot name="header">
        <p style="font-size: 48px; font-weight: bold; background: linear-gradient(to right, #800080, #FFD700, #00FF00, #0000FF); -webkit-background-clip: text; color: transparent;">
            Your <span style="background: linear-gradient(to right, #0000FF, #00FF00, #FFD700, #800080); -webkit-background-clip: text; color: transparent;">MUSIC</span>
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout>
