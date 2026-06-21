<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diet Options</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans min-h-screen flex items-center justify-center relative bg-fixed bg-cover bg-center bg-no-repeat"style="background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20231028/pngtree-fresh-and-calming-watercolor-texture-background-in-light-mint-pastel-green-image_13758848.png');">

<div class="max-w-2xl w-full mx-auto">

    {{-- Title centered at top --}}
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 text-center sm:mb-8">
        Diet Options
    </h1>

    {{-- Diet Options Grid --}}
    <div class="grid gap-4 sm:gap-6 md:gap-8">
        @foreach($dietPlans as $plan)
            <form action="/save-diet" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="diet" value="{{ $plan['name'] }}">

                <button type="submit"
                    class="w-full bg-white p-5 sm:p-8 rounded-2xl shadow-lg border-b-4 border-blue-500 hover:scale-105 transition-transform text-center">

                    <span class="text-xs font-bold text-blue-600 uppercase bg-blue-50 px-3 py-1 rounded-full inline-block">
                        {{ $plan['tag'] }}
                    </span>

                    <h2 class="font-bold text-xl sm:text-2xl mt-3 sm:mt-4 break-words">
                        {{ $plan['name'] }}
                    </h2>

                    <p class="text-gray-500 text-xs sm:text-sm mt-2 sm:mt-3 break-words">
                        {{ $plan['description'] }}
                    </p>
                </button>
            </form>
        @endforeach
    </div>
</div>
</body>
</html>
