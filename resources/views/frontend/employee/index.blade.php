@extends('frontend.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card  text-dark">
      <style>
        th {
          border-top: 1px solid #dddddd;
        }
      </style>
      <div class="row p-4">
        <div class="col-12">
          <div class="table-responsive text-nowrap">
            <table id="example" class=" text-dark table" style="width:100%">
              <thead class="border-top">
                <tr class="text-dark ">
                  <th>Name</th>
                  <th>Job title</th>
                  <th>Phone</th>
                  <th>Email</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            ajax: './data.txt',
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "bPaginate": false,
            processing: true,
            serverSide: true,
    
    
            dom: '<"toolbar">frtip',
            columns: [
    
                {
                    "render": function(data, type, full, meta) {
    
                        return '<div class="mb-2 "><img src="./assets/img/avatars/' + full.profile +
                            '" alt="Avatar" class="avatar avatar-xs rounded-circle"> <strong>' +
                            full.name + '</strong></div>';
    
                    }
                },
    
                {
                    data: 'position'
                },
                {
                    data: 'office'
                },
                {
                    data: 'email'
                },
            ],
            order: [
                [1, 'asc']
            ],
    
        });
    
        $('div.toolbar').html(
            '<div class="row p-4"><div class="col-md-4 col-12 "><b><a class=" text-dark" href="">Active 30</a></b> | <b><a class="mt-auto text-dark text-muted" href="">Terminated 30</a></b></div><div class="col-md-4 col-12 "><div class="input-group input-group-merge"><span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span><input type="text" class="form-control" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon-search31"></div></div><div class="col-md-2 col-12 "><div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Import/Export</button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:void(0);">Import Data</a></li><li><a class="dropdown-item" href="javascript:void(0);">Export Data</a></li></ul></div></div><div class="col-md-2 col-12 "><a href="onboardemployees.php" class="btn btn-primary">Add employee</a></div></div>'
        );
    
    
        $('#example tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            window.location = "employees_detail.php";
        });
    
    });
    </script>
@endpush