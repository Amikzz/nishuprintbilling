<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items List</title>
    <style>
        /* Your existing CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .home-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .home-button:hover {
            background: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #dddddd;
            text-align: left;
        }

        table th {
            background: #f8f9fa;
        }

        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #a71d2a;
        }

        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination li a {
            display: block;
            padding: 8px 12px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination li a:hover {
            background: #0056b3;
        }

        .pagination li.active a {
            background: #0056b3;
            cursor: default;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            width: 50%;
            min-height: 500px;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        form div {
            margin-bottom: 15px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="number"] {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
        <!-- Button to open modal -->
    <button onclick="openModal()" class="home-button">Add Item</button>

    <!-- Modal Form for Adding Item -->
    <div id="itemModal" class="modal">
        <div class="modal-content">
            <span onclick="closeModal()" class="close">&times;</span>
            <h2>Add New Item</h2>
            <form method="POST" action="{{route("admin.items.store")}}">
                @csrf
                <div>
                    <label for="item_code">Item Code (Reference)</label>
                    <input type="text" name="item_code" id="item_code" required class="form-control">
                </div>
                <div>
                    <label for="name">Item Name</label>
                    <input type="text" name="name" id="name" required class="form-control">
                </div>
                <div>
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                </div>
                <div>
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" required class="form-control" step="0.0001">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" onclick="closeModal()" class="btn btn-danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <h1>Items List</h1>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description ?? '-' }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->created_at }}</td>
                <td>
                    <a href="#" class="btn btn-primary">Edit</a>
                    <form action="#" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <ul class="pagination">
        {{ $items->links() }}
    </ul>
</div>

<script>
    // Get modal element
    var modal = document.getElementById("itemModal");

    // Function to open the modal
    function openModal() {
        // Ensure that only one modal is open
        if (modal.style.display !== "flex") {
            modal.style.display = "flex";
        }
    }

    // Function to close the modal
    function closeModal() {
        modal.style.display = "none";
    }

    // Close modal if clicked outside the modal
    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    }
</script>

</body>
</html>
