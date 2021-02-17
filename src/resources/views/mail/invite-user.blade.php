@component('mail::message')
# Hi {{$user->name}}


@if($ability=="read")
You have been granted Read access for {{$entity}} with id {{$model->id}}
@elseif($ability=="write")
You have been granted Write access for {{$entity}} with id {{$model->id}}
@elseif($ability=="remove")
Access revoked from {{$entity}} with id {{$model->id}}
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent