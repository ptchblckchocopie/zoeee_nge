<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Agrandir', sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #1e40af;
            color: white;
            padding: 1rem;
            text-align: center;
            position: relative;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .to-do-list {
            padding: 1rem;
            border-radius: 0.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 1rem;
            text-align: left;
        }
        th {
            background-color: #1e40af;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f7fafc;
        }
        tr:hover {
            background-color: #e2e8f0;
        }
        .btn-delete {
            background-color: #ff4500;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .dropdown {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
        }
        .user-menu {
            cursor: pointer;
        }
        .user-menu button {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            top: 30px;
            right: 0;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            z-index: 1;
        }
        .dropdown-content a,
        .dropdown-content form button {
            display: block;
            padding: 10px 20px;
            text-align: left;
            text-decoration: none;
            color: #1e40af;
            border: none;
            background-color: white;
            width: 100%;
        }
        .dropdown-content a:hover,
        .dropdown-content form button:hover {
            background-color: #f0f0f0;
        }
        .btn-backup {
            background-color: #4caf50;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            margin-top: 1rem;
        }
    </style>
    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById('dropdown');
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        }
        window.onclick = function(event) {
            if (!event.target.matches('.user-menu button')) {
                var dropdown = document.getElementById('dropdown');
                if (dropdown.style.display === 'block') {
                    dropdown.style.display = 'none';
                }
            }
        }

        function runBackup() {
            fetch('{{ route('run-backup') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <div class="header">
        <h2>DailyDoer</h2>
        <div class="dropdown">
            @auth
            <div class="user-menu">
                <button onclick="toggleDropdown()">Hello, {{ Auth::user()->name }}</button>
                <div class="dropdown-content" id="dropdown">
                    <a href="{{ route('profile.edit') }}">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </div>
    <div class="container">
        <div class="to-do-list">
            <div class="card">
                <p>Welcome, ADMIN :)</p>
                <table class="table">
                <thead class="text-primary">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>	
                        <th>Email Verified At</th>
                        <th>User Type</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>asda</td>
                        <td>asdas</td>
                        <td>asda</td>
                        <td>asda</td>
                        <td><button type="submit" class="btn-delete">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button class="btn-backup" onclick="runBackup()">Run Backup</button>
            </div>
        </div>
    </div>
</body>
</html>
