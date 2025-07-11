@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2 style="text-align:center;">Animals</h2>
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
        $('#animal_type').on('change', function () { // This listens for the change event on the <select> dropdown with the ID animal_type
            const animalTypeId = $(this).val();// Gets the selected value (the animal_type_id) from the dropdown.

            if (!animalTypeId) {
                location.reload(); // Reload the page if no type is selected
                return;
            }

            $.ajax({ // Starts an AJAX request using jQuery to send data to the server without reloading the page.
                url: `/animal/filter`,// This is the URL Laravel expects to receive AJAX requests  to filter animals.//It points to your filterByType() method in the AnimalController
                method: 'GET',// The type of request is GET, meaning we're just retrieving data, not changing it.
                data: { animal_type_id: animalTypeId },//This sends the selected animal type ID to the server as a parameter in the request.// Example: /animal/filter?animal_type_id=2

                success: function (data) //If the request is successful, this function will run.
                {

                    const $animalList = $('#animals_list');// This targets the <tbody> where animals are listed.//Stores it in a variable so it can be updated.

                    $animalList.empty();// Clears the current contents (rows) of the animal table body, so we can insert the filtered ones.

                    if (data.length > 0) { //Checks if any animals were returned from the server.

                        data.forEach(function (animal) {//Loops through each animal object in the returned list.
                            $animalList.append(//Adds a new row (<tr>) for each animal to the HTML table dynamically.
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
                    } else 
                    {
                        $animalList.append('<tr><td colspan="5" class="text-center">No animals found for the selected type.</td></tr>');// Adds one table row that spans all 5 columns and says: “No animals found…”
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
