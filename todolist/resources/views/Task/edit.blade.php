<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Task</title>
    <!-- Include the Indie Flower font from Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Indie+Flower&display=swap">
    <style>
        body {
            background-color: #FFF9D0;
            font-family: 'Indie Flower', cursive;
        }
        header {
            background-color: #5AB2FF;
            color: white;
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .edit-section {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .edit-form input[type="text"],
        .edit-form select {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .edit-form button[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #5AB2FF;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .edit-form button[type="submit"]:hover {
            background-color: #4584d4;
        }
        .edit-form label {
            margin-bottom: 5px;
            display: block;
        }
        .delete-form {
            text-align: center;
            margin-top: 20px;
        }
        .delete-form button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .delete-form button:hover {
            background-color: #ff1a1a;
        }
    </style>
</head>
<body>
    <header onclick="window.location='{{ route('tasks.index') }}';">
        <h1>To Do List</h1>
    </header>
    <div class="container">
        <div class="edit-section">
            <h2>Edit Task</h2>
            <form class="edit-form" method="POST" action="{{ route('tasks.update', $todo->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="todo_id" value="{{ $todo->id }}">
                <label for="titleInput">Title</label>
                <input type="text" id="titleInput" name="title" placeholder="Title" value="{{ old('title', $todo->title) }}" required>
                <label for="descriptionInput">Description</label>
                <input type="text" id="descriptionInput" name="description" placeholder="Description" value="{{ old('description', $todo->description) }}" required>
                <label for="statusSelect">Status</label>
                <select name="completed" id="statusSelect" required>
                    <option value="0" {{ !$todo->completed ? 'selected' : '' }}>Incomplete</option>
                    <option value="1" {{ $todo->completed ? 'selected' : '' }}>Completed</option>
                </select>
                <button type="submit">Update</button>
            </form>
            <form class="delete-form" method="POST" action="{{ route('tasks.destroy', $todo->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>
    </div>
</body>
</html>
