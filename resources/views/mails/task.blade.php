<!DOCTYPE html>
<html>

<head>
    <title>{{ _('Task status') }}</title>
</head>

<body>
    <p><strong>{{ _('Task info') }}</strong></p>
    <p><strong>{{ _('Name:') }}</strong> {{ $task->name }}</p>
    <p><strong>{{ _('Status:') }}</strong> {{ _($task->status) }}</p>
    <p><strong>{{ _('uuid:') }}</strong> {{ $task->uuid }}</p>
    <p><strong>{{ _('Created at:') }}</strong> {{ $task->created_at }}</p>
    <br>
    <p><strong>{{ _('Layer info') }}</strong></p>
    <p><strong>{{ _('Name:') }}</strong> {{ $task->layer->name }}</p>
    <p><strong>{{ _('Type:') }}</strong> {{ _('layer_type_' . strtolower($task->layer->layerType->name)) }}</p>
</body>

</html>
