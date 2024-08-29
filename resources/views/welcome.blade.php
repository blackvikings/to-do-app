<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <style>
            .flash-message {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                border-radius: 5px;
                z-index: 9999;
            }

            .flash-message.success {
                background-color: #4CAF50; /* Green */
                color: white;
            }

            .flash-message.error {
                background-color: #f44336; /* Red */
                color: white;
            }
        </style>
    </head>
    <body>

        <div class="container">
            <div class="row">
                <div class="col-md-6 mt-5">
                    <h1>PHP - Simple To Do List App</h1>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control mt-4" id="add-task-value">
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary mt-4 add-task-submit">Add Task</button>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Task</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }} <input type="hidden" value="{{ $item->id }}"></td>
                                        <td>{{ $item->name }}</td>
                                        <td>@if($item->status == true) Done @endif</td>
                                        <td>
                                            <button class="btn btn-success update"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>
                                            <button class="btn btn-danger remove"><i class="fa fa-times" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

        <script>


            $(document).ready(function () {

                function showFlashMessage(message, type) {
                    var $flash = $('<div class="flash-message ' + type + '">' + message + '</div>');
                    $('body').append($flash);
                    $flash.fadeIn(300).delay(3000).fadeOut(300, function() {
                        $(this).remove();
                    });
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('.add-task-submit').click(function () {
                    let addTaskValue = $("#add-task-value").val();
                    console.log(addTaskValue);
                    $.ajax({
                        url: '{{ route('item.store') }}',
                        type: 'POST',
                        data: { name : addTaskValue},
                        success: function (res) {
                            console.log(res);
                            showFlashMessage(res.message, 'success');
                            let rowCount = $('#myTable >tbody >tr').length;
                            rowCount++;
                            let newTableRow = '<tr>'+
                                                    '<td>'+
                                                        rowCount+
                                                        '<input type="hidden" value="'+ res.item.id +'">'+
                                                    '</td>'+
                                                    '<td>'+
                                                        res.item.name+
                                                    '</td>'+
                                                    '<td>'+
                                                    '</td>'+
                                                    '<td>'+
                                                        '<button class="btn btn-success update"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>&nbsp;'+
                                                        '<button class="btn btn-danger remove"><i class="fa fa-times" aria-hidden="true"></i></button>'+
                                                    '</td>'+
                                                '</tr>';
                            $('#tbody').append(newTableRow);
                        }
                    })
                })

                $('#tbody').on('click', '.remove', function () {
                    id = $(this).closest('tr').find('td:first-child input').val();
                    console.log(id);
                    $.ajax(
                        {
                            url: "/"+id,
                            type: 'delete',
                            dataType: "JSON",
                            data: { "id": id },
                            success: function (response) {
                                console.log(response);
                                showFlashMessage(response.message, 'success');
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    $(this).parent('td').parent('tr').remove();
                });

                $('#tbody').on('click', '.update', function () {
                    id = $(this).closest('tr').find('td:first-child input').val();
                    console.log(id);
                    $.ajax(
                        {
                            url: "/"+id,
                            type: 'patch',
                            dataType: "JSON",
                            data: { "id": id },
                            success: function (response) {
                                console.log(response);
                                showFlashMessage(response.message, 'success');
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    $(this).closest('tr').find('td:nth-child(3)').html('Done');
                });
            })
        </script>
    </body>
</html>
