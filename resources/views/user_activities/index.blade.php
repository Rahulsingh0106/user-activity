<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activity Listing</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .filter-form {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>User Activity Listing</h2>
                <form action="{{ route('recalculate-ranks') }}" method="POST">
                    @csrf
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary">Recalculate</button>
                    </div>
                </form>
                <form class="filter-form" method="{{route('/')}}">
                    <div class="form-row align-items-end">
                        <!-- Search Bar -->
                        <div class="form-group col-md-4">
                            <input type="search" name="user_id" class="form-control" id="search" placeholder="Search by user id">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>

                        <!-- Sort By Dropdown -->
                        <div class="form-group col-md-3">
                            <label for="sort-by">Sort By</label>
                            <select class="form-control" id="sort-by" name="sort_by">
                                <option value="day" {{ request('sort_by') == 'day' ? 'selected' : '' }}>Day</option>
                                <option value="month" {{ request('sort_by') == 'month' ? 'selected' : '' }}>Month</option>
                                <option value="year" {{ request('sort_by') == 'year' ? 'selected' : '' }}>Year</option>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Points</th>
                            <th>Rank</th>
                        </tr>
                    </thead>
                    <tbody id="user-list">
                        @foreach ($userActivities as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->full_name}}</td>
                            <td>{{$user->points}}</td>
                            <td>{{$user->rank}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>