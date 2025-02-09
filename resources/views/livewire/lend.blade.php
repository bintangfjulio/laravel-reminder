@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <h1 class="font-bold text-2xl">List Lend</h1>

    <div class="flex justify-between my-6 items-end">
        <div class="flex space-x-4">
            <label class="form-control">
                <div class="label">
                    <span class="label-text">Lend Start</span>
                </div>
                <input type="datetime-local" wire:model.live="filter_start" class="input input-bordered input-md w-full">
            </label>

            <label class="form-control">
                <div class="label">
                    <span class="label-text">Lend End</span>
                </div>
                <input type="datetime-local" wire:model.live="filter_end" class="input input-bordered input-md w-full">
            </label>

            @if ($filter_start || $filter_end)
                <div class="flex items-end mb-2">
                    <button class="btn btn-sm btn-outline" wire:click="resetFilters">
                        Reset Filters
                    </button>
                </div>
            @endif
        </div>

        @if (Auth::user()->role == 'user')
            <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                wire:click="openModal('tambah')">
                Add Lend
            </button>
        @endif
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra">
            <thead class="text-black font-bold">
                <tr class="text-md text-center">
                    <td>No</td>
                    @if (Auth::user()->role == 'admin')
                        <td>Borrower</td>
                    @endif
                    <td>Room</td>
                    <td>Lend Start</td>
                    <td>Lend End</td>
                    <td>Status</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                @forelse ($lends as $lend)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}.</td>
                        @if (Auth::user()->role == 'admin')
                            <td>{{ $lend->user->name }}</td>
                        @endif
                        <td>{{ $lend->room->name }}</td>
                        <td>{{ Carbon::parse($lend->lend_start)->translatedFormat('d F Y H:i') }}
                        <td>{{ Carbon::parse($lend->lend_end)->translatedFormat('d F Y H:i') }}
                        <td>{{ $lend->status_label }}</td>
                        <td>
                            <div class="flex justify-center items-center gap-2">
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                                    wire:click="openModal('lihat', {{ $lend->id }})">
                                    View
                                </button>
                                @if (Auth::user()->role == 'admin' || (Auth::user()->role == 'user' && $lend->status == 1))
                                    <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                                        wire:click="openModal('edit', {{ $lend->id }})">
                                        Edit
                                    </button>
                                    <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                                        wire:click="openModal('hapus', {{ $lend->id }})">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $lends->links() }}
    </div>

    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>

            @if ($modalAction === 'hapus')
                <p>Are you sure to delete this data</span>?
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
                    <label class="form-control mb-2">
                        <div class="label">
                            <span class="label-text">Room <span class="text-red-500">*</span></span>
                        </div>
                        <select wire:model.blur="room_id" {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="select select-bordered select-md @error('room_id') border-red-500 @enderror">
                            <option value="" disabled selected>Select Room</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>

                        @error('room_id')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="form-control mb-2">
                            <div class="label">
                                <span class="label-text">Lend Start <span class="text-red-500">*</span></span>
                            </div>
                            <input type="datetime-local" wire:model="lend_start"
                                {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                                class="input input-bordered input-md w-full @error('lend_start') border-red-500 @enderror">
                            @error('lend_start')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="form-control mb-2">
                            <div class="label">
                                <span class="label-text">Lend End <span class="text-red-500">*</span></span>
                            </div>
                            <input type="datetime-local" wire:model="lend_end"
                                {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                                class="input input-bordered input-md w-full @error('lend_end') border-red-500 @enderror">
                            @error('lend_end')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    @if ($modalAction != 'tambah')
                        <label class="form-control mb-2">
                            <div class="label">
                                <span class="label-text">Status <span class="text-red-500">*</span></span>
                            </div>
                            <select wire:model.blur="status"
                                {{ $modalAction === 'lihat' || Auth::user()->role == 'user' ? 'disabled' : '' }}
                                class="select select-bordered select-md @error('status') border-red-500 @enderror">
                                @foreach (\App\Models\Lend::STATUSES as $id => $value)
                                    <option value="{{ $id }}">{{ $value }}</option>
                                @endforeach
                            </select>

                            @error('status')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>
                    @endif

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
