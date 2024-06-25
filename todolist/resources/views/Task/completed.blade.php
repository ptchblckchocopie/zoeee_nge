<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Completed Tasks</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Indie+Flower&display=swap">
    <style>
        body {
            background-color: #FFF9D0;
            font-family: 'Indie Flower', cursive;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #5AB2FF;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .completed-task {
            color: green;
            font-weight: bold;
        }
        .description-cell {
            word-wrap: break-word;
            white-space: normal;
            max-width: 300px;
        }

        .btn-info {
            background-color: #A9A9A9;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-info:hover {
            background-color: #4584d4;
        }
        .back-button-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .delete-button {
            background-color: #FF6347;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .delete-button:hover {
            background-color: #d93c23;
        }


    </style>
</head>
<body>
    <header>
        <h1>Completed Tasks</h1>
    </header>
    <div class="container">
    <div class="back-button-container">
            <a class="btn-info" href="{{ route('tasks.index') }}">Back</a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Completed At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completedTodos as $todo)
                        <tr>
                            <td class="completed-task">{{ $todo->title }}</td>
                            <td class="description-cell completed-task">{{ $todo->description }}</td>
                            <td class="completed-task">{{ $todo->updated_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <form method="POST" action="{{ route('tasks.destroyCompleted', $todo->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button">Delete</button>
                                </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($completedTodos->isEmpty())
                <h4>No Completed Tasks</h4>
            @endif
        </div>
    </div>
</body>
</html>
