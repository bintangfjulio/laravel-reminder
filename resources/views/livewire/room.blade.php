<div class="px-5 py-4">
    <h1 class="font-bold text-2xl">List Room</h1>

    <div class="flex justify-between my-6 items-center">
        <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-3 w-3/5 md:w-1/4">
            <input wire:model.live.debounce.400ms="search" type="text"
                class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full"
                placeholder="Search name..." />
        </label>

        <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
            wire:click="openModal('tambah')">
            Add Room
        </button>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra">
            <thead class="text-black font-bold">
                <tr class="text-md text-center">
                    <td>No</td>
                    <td>Name</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                @forelse ($rooms as $room)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $room->name }}</td>
                        <td>
                            <div class="flex justify-center items-center gap-2">
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                                    wire:click="openModal('lihat', {{ $room->id }})">
                                    View
                                </button>
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                                    wire:click="openModal('edit', {{ $room->id }})">
                                    Edit
                                </button>
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                                    wire:click="openModal('hapus', {{ $room->id }})">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $rooms->links() }}
    </div>

    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>

            @if ($modalAction === 'hapus')
                <p>Are you sure to delete room <span class="text-red-500 font-medium">
                        {{ $name }}</span>?
                </p>
                <div class="modal-action">
                    <div class="flex space-x-2 justify-end">
                        <button
                            class="btn btn-sm btn-outline text-black border-black hover:bg-black hover:text-white hover:border-none"
                            wire:click="resetModal">Close</button>

                        <button class="btn btn-error btn-sm text-white" wire:click="delete">Yes, delete</button>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="saveData">
                    <div class="form-control mb-2">
                        <label class="label">
                            <span class="label-text">Name <span class="text-red-500">*</span></span>
                        </label>
                        <input wire:model.blur="name" type="text" placeholder="Name"
                            {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="input input-bordered @error('name') border-red-500 @enderror" required />
                        @error('name')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="modal-action">
                        <div class="flex space-x-2 justify-end">
                            <button type="button"
                                class="btn btn-sm btn-outline text-black border-black hover:bg-black hover:text-white hover:border-none"
                                wire:click="resetModal">Close</button>

                            @if ($modalAction != 'lihat')
                                <button type="submit"
                                    class="btn btn-sm bg-black text-white">{{ $modalAction === 'edit' ? 'Save' : 'Add' }}</button>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </dialog>
</div>
