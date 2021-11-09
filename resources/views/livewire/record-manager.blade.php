<div>
    @foreach ($module->fields() as $field)
        <div>{{  $field->name }}</div>
    @endforeach
</div>
