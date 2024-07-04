<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Meeting</title>
</head>
<body>
@if(session()->has('success'))
<div class="alert alert-success">
    <p>{{session('success')}}</p>
</div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger">
        <p>{{session('error')}}</p>
    </div>
@endif
<h1>hjni</h1>
<h1>Create a Zoom Meetinvg</h1>
<form action="{{ url('create-meeting') }}" method="POST">
    @csrf
    <label for="topic">Meeting Topic:</label><br>
    <input type="text" id="topic" name="topic" required><br>

    <label for="start_time">Start Time:</label><br>
    <input type="datetime-local" id="start_time" name="start_time" required><br>

    <label for="agenda">Agenda:</label><br>
    <textarea id="agenda" name="agenda" required></textarea><br>

    <button type="submit">Create Meeting</button>
</form>
</body>
</html>
