@props(['disabled' => false,'options'=>[]])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md w-full shadow-sm']) !!}>

@foreach ($options as  $i=>$option)
               
               <option value="{{ $i }}">{{ $option}}</option>     
             
        @endforeach

</select>