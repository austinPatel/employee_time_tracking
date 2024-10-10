<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Log Your Work Hours</h2>
    @if (session()->has('message'))
        <div id ="successMessage" class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="save">
        {{ $this->form }}
        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">{{ $timeLogId ? 'Update TimeLog' : 'Create TimeLog' }}</button>
        </div>
    </form>
    @script
    <script>
        setTimeout(function() {
            alert('timeout ran')
            $('#successMessage').remove();
        }, 5000); 
    </script>
    @endscript
</div>