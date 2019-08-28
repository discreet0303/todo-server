<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Todo List</title>
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    <script src="{{ asset('/js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('/js/jquery-3.4.1.min.js') }}"></script>

</head>
<body>
    <div class="container">
        <h1 class="todo-title">Todo List</h1>
        <div id="todo-list" class="todo-list">
            <form class="todo-add-item" action="/todo/create" method="POST">
                @csrf
                <input type="text" name="todo">
                <button type="submit">Submit</button>
            </form>
            @forelse ($todos as $todo)
                <div id="todo-item-{{$todo->id}}" class="todo-item">
                    <input type="checkbox" name="" id=""> 
                    {{ $todo->todo }}
                    <button class="todo-del-bt" onclick="delTodo({{$todo->id}})">x</button>
                </div>
            @empty
                <p style="text-align:center;">目前無 Todo 事項</p>
            @endforelse
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("input[type=checkbox]").change(function() {
                parentEle = $(this).parent();
                if(this.checked) parentEle.css('text-decoration', 'line-through');
                else parentEle.css('text-decoration', 'none');
            });
        });
        function delTodo(todoId) {
            $.ajax({
                type: 'DELETE',
                url: '/todo/delete/' + todoId,
                data: {
                    '_token': @json(csrf_token()),
                },
                success: function(res) {
                    Swal.fire({
                        type: 'success',
                        title: '刪除成功'
                    });
                    $(`#todo-item-${todoId}`).remove();
                    todoItemNum = $('div.todo-item').find().prevObject.length;
                    if (todoItemNum === 0) $('#todo-list').append('<p style="text-align:center;">目前無 Todo 事項</p>');
                },
            });
        }
    </script>

    @if (session('status'))
        <script>
            var msg = @json(session('status'));
            var swal = {
                type: (msg === 'fail') ? 'error' : 'success',
                title: (msg === 'fail') ? @json($errors->first('todo')) : '新增成功'
            }
            Swal.fire(swal);
        </script>
    @endif
</body>
</html>