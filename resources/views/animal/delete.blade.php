<form method="post" action="{{route('animal.destroy',$animaldetail->id)}}">
            @csrf
               <p>Are you sure want to delete?</p>
               <p>following will be deleted</p>
               <p>title:{{$animaldetail->animal_name}}</p>
                <input type="submit" value="delete">

        </form>