@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Animals</h2>
                </div>

                <div class="float-right">
                    <a class="btn btn-success btn-md btn-rounded" href="{{route('animal.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add Animal
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Filter by Animal Type -->
                <div class="mb-3">
                    <label for="animal_type" class="form-label">Filter by Animal Type</label>
                    <select id="animal_type" name="animal_type_id"class="form-control">
                        <option value="">Select Animal Type</option>
                        @foreach($animal_types as $type)
                            <option value="{{ $type->id }}">{{ $type->animal_type }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Form -->
                <form action="{{ route('animal.list') }}" method="GET" class="mb-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <input
                                type="text"
                                name="q"
                                value="{{ request()->input('q') }}"
                                placeholder="Search"
                                class="form-control"
                            >
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-success btn-md">Search</button>
                        </div>
                    </div>
                </form>

                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Animal Name</th>
                            <th>Animal Type</th>
                            <th>Age</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="animals_list">
                        @foreach($animals as $animal)
                        <tr>
                            <td>{{ $animal->id }}</td>
                            <td>{{ $animal->animal_name }}</td>
                            <td>{{ $animal->AnimalType->animal_type }}</td>
                            <td>{{ $animal->age }}</td>
                            <td>
                                <a href="{{ route('animal.view', $animal->id) }}" class="btn btn-info">View</a>
                                <a href="{{ route('animal.edit', $animal->id) }}" class="btn btn-primary">Edit</a>
                                <button class="btn btn-danger" onclick="confirmDelete({{ $animal->id }})">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <form id="deleteForm" method="post" style="display:none;">
                    @csrf
                    @method('POST')
                </form>

                <div class="pt-2">
                    <div>
                        {{ $animals->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




<script>
   function confirmDelete(animalId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the animal record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/animal/${animalId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
    $(document).ready(function () {
        // Filter animals by type dynamically
        $('#animal_type').on('change', function () {
            const animalTypeId = $(this).val();

            if (!animalTypeId) {
                location.reload(); // Reload the page if no type is selected
                return;
            }

            $.ajax({
                url: `/animal/filter`,
                method: 'GET',
                data: { animal_type_id: animalTypeId },
                success: function (data) {
                    const $animalList = $('#animals_list');
                    $animalList.empty();

                    if (data.length > 0) {
                        data.forEach(function (animal) {
                            $animalList.append(
                                `<tr>
                                    <td>${animal.id}</td>
                                    <td>${animal.animal_name}</td>
                                    <td>${animal.animal_type}</td>
                                    <td>${animal.age}</td>
                                    <td>
                                        <a href="/animal/${animal.id}/view" class="btn btn-info">View</a>
                                        <a href="/animal/${animal.id}/edit" class="btn btn-primary">Edit</a>
                                        <button class="btn btn-danger" onclick="confirmDelete(${animal.id})">Delete</button>
                                    </td>
                                </tr>`
                            );
                        });
                    } else {
                        $animalList.append('<tr><td colspan="5" class="text-center">No animals found for the selected type.</td></tr>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });

        
    });
</script>







@endsection
