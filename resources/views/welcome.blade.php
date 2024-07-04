<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZoomOauth</title>
</head>
<body>

{!! Form::open(['url' => 'create-meeting', 'method' => 'post', 'id' => 'zoomMeetingForm']) !!}
{!! csrf_field() !!}

<div class="form-group">
    {!! Form::label('topic', 'Meeting Topic:') !!}
    {!! Form::text('topic', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('start_time', 'Start Time:') !!}
    {!! Form::datetimeLocal('start_time', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('agenda', 'Meeting Agenda:') !!}
    {!! Form::textarea('agenda', null, ['class' => 'form-control', 'required']) !!}
</div>

{!! Form::submit('Create Meeting', ['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}


{{--<a href="{{ url('start') }}">Make A Zoom Meeting Using Oauth2 And Laravel</a>--}}
<br/>
<br/>
<br/>
{{ $respond }}


<script>
    document.getElementById('zoomMeetingForm').addEventListener('submit', function(event) {
        const topic = document.getElementById('topic').value;
        const startTime = document.getElementById('start_time').value;
        const agenda = document.getElementById('agenda').value;

        if (!topic || !startTime || !agenda) {
            alert('Please fill out all required fields.');
            event.preventDefault();
        }
    });
</script>

</body>
</html>
